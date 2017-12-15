<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\Invoice;
use App\Constants\Activities;
use App\Jobs\RecordActivity;
use App\Jobs\ProcessPayment;
use App\Jobs\ProcessUpgrade;
use App\Jobs\LinkUpgradeCost;
use App\Facades\LocaleRouteFacade;


use Auth;
use Config;
use Lang;

class PaypalController extends Controller
{
    protected $provider;

    public function __construct() {
        
        $this->middleware('auth');

        $this->provider = new ExpressCheckout();
    }

    public function expressCheckout(Request $request) {
        
        // check if payment is recurring
        //$recurring = $request->input('recurring', false) ? true : false;
        $recurring = false;

        $user = Auth::user();
        $kit = $request->session()->get('kit');

        if (is_null($kit))
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        // Save the kit
        $kit->save();
      
        // set invoice id        
        $invoice_id = strtoupper(Config::get('app.origin')) . $kit->barcode;

        // Get the cart data
        $cart = $this->getCart($request, $invoice_id);

        $upgrade = $request->session()->get('upgrade');

        $test_price = 0;
        $analysis_cost = 0;
        
        if (count($cart['items']) == 1) 
        {
            if (!is_null($upgrade)) 
            {
                $test_price = $cart['items'][0]['price'];
            } 
            else
            {
                $analysis_cost = $cart['items'][0]['price'];
            }
        } 
        else if (count($cart['items']) == 2) 
        {
            $test_price = $cart['items'][0]['price'];
            $analysis_cost = $cart['items'][1]['price'];
        }

        // create new invoice
        $invoice = new Invoice();
        $invoice->title = $cart['invoice_description'];
        $invoice->test_price = $test_price;
        $invoice->analysis_cost = $analysis_cost;
        $invoice->total = $cart['total'];
        $invoice->paypal_invoice_id = $invoice_id;
        $invoice->user_id = $user->id;
        $invoice->kit_registration_id = $kit->id;
        $invoice->save();

        // send a request to paypal 
        // paypal should respond with an array of data
        // the array should contain a link to paypal's payment system
        $response = $this->provider->setExpressCheckout($cart, $recurring);
      
        // if there is no link redirect back with error message
        if (!$response['paypal_link']) {
            //$request->flash('alert-danger', $response['L_LONGMESSAGE0']);

            if ($response['L_LONGMESSAGE0'])            
            {
                $invoice->payment_status = 'Invalid';
                $invoice->error_msg = $response['L_LONGMESSAGE0'] . ': ' . $cart['return_url'] ;
                $invoice->save();
            }

            return redirect(LocaleRouteFacade::route('kit.new'))->with('alert-danger', Lang::get('products.paypal-connect-error'));
        }
      
        // redirect to paypal
        // after payment is done paypal
        // will redirect us back to $this->expressCheckoutSuccess
        return redirect($response['paypal_link']);
      }

      private function getCart($request, $invoice_id)
      {
  
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');
      
        $product_name = is_null($upgrade) ? $kit['test_name'] : $upgrade->test;
 
        $items = [];
        $total = 0;

        if (!is_null($upgrade) && $upgrade->upgradePrice > 0)
        {
            $items[] = [
                'name' => 'Test Upgrade (' . $product_name . ')',
                'price' => $upgrade->upgradePrice,
                'qty' => 1
            ];
            $total += $upgrade->upgradePrice;
        }

        if ($inquiry->billingMethod == 'With Sample' && $inquiry->analysisCostDue > 0)
        {
            $items[] = [
                'name' => 'Analysis Cost (' . $product_name . ')',
                'price' => $inquiry->analysisCostDue,
                'qty' => 1
            ];
            $total += $inquiry->analysisCostDue;
        }
        
        return [
            // if payment is not recurring cart can have many items
            // with name, price and quantity
            'items' => $items,

            // return url is the url where PayPal returns after user confirmed the payment
            'return_url' => url(LocaleRouteFacade::url('/paypal/express-checkout-success')),
            // every invoice id must be unique, else you'll get an error from paypal
            'invoice_id' => $invoice_id,
            'invoice_description' => "Order #" . $invoice_id . " " . $product_name,
            'cancel_url' => url(LocaleRouteFacade::url('/paypal/express-checkout-cancel')),
            // total is calculated by multiplying price with quantity of all cart items and then adding them up
            'total' => $total,
        ];
      }

      public function expressCheckoutCancel(Request $request)
      {
            $user = Auth::user();
            $kit = $request->session()->get('kit');
        
            if (is_null($kit))
            {
                return redirect(LocaleRouteFacade::route('home'))->with('alert-danger', Lang::get('products.payment-cancelled'));
            }

            return redirect(LocaleRouteFacade::route('kit.payment', [ 'barcode' => $kit->barcode]))->with('alert-danger', Lang::get('products.payment-cancelled'));
      }

      public function expressCheckoutSuccess(Request $request) {
        
        $user = Auth::user();
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');

        $token = $request->get('token');

        $PayerID = $request->get('PayerID');

        // initaly we paypal redirects us back with a token
        // but doesn't provice us any additional data
        // so we use getExpressCheckoutDetails($token)
        // to get the payment details
        $response = $this->provider->getExpressCheckoutDetails($token);

        
        // if response ACK value is not SUCCESS or SUCCESSWITHWARNING
        // we return back with error
        if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

            dispatch(new RecordActivity($request, $user, Activities::PAYPAL_PAYMENT_FAILURE, $response['ACK']));
            
            return redirect(LocaleRouteFacade::route('kit.payment', ['barcode' => $kit['barcode']]))->with('alert-danger', Lang::get('products.paypal-payment-error'));
        }

        
        // invoice id is stored in INVNUM
        $invoice_id = $response['INVNUM'];
        
        // get cart data
        $cart = $this->getCart($request, $invoice_id);

        // Perform transaction on PayPal
        // and get the payment status
        $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);

        $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS']; 

        // find invoice by id
        $invoice = Invoice::where('paypal_invoice_id', $invoice_id)->first();

        if (is_null($invoice))
        {
            return redirect(LocaleRouteFacade::route('kit.payment', ['barcode' => $kit['barcode']]))->with('alert-danger', Lang::get('products.paypal-payment-error'));
        }

        // set invoice status
        $invoice->payment_status = $status;

        // set user id
        $invoice->user_id = $user->id;

        // set kit id
        $invoice->kit_registration_id = $kit->id;
    
        // save the invoice
        $invoice->save();
        $request->session()->put('invoice', $invoice);

        if (!$invoice->paid)
        {
            return redirect(LocaleRouteFacade::route('kit.payment', ['barcode' => $kit['barcode']]))->with('alert-danger', Lang::get('products.paypal-payment-error'));
        }

        dispatch(new RecordActivity($request, $user, Activities::PAYPAL_PAYMENT_SUCCESS, 'Invoice #' . $invoice_id));
 
        if ($inquiry->billingMethod == "With Sample" && $inquiry->analysisCostDue > 0)
        {
            dispatch(new ProcessPayment($user, 'AnalysisCostPayment', $kit['barcode'], $inquiry->analysisCostDue));
        }

        if (!is_null($upgrade))
        {

            // Upgrade the kit registration
            $kit->test_name = $upgrade->test;
            $kit->test_id = $upgrade->testId;
            $kit->test_price = $upgrade->testPrice;
            $kit->save();

            dispatch(new ProcessUpgrade($user, $kit['barcode'], $upgrade));     

            if ($upgrade->upgradePrice > 0)
            {
                dispatch(new ProcessPayment($user, 'TestUpgradePayment', $kit['barcode'], $upgrade->upgradePrice));
            }
        }

        // update the inquiry
        $inquiry->requiresPayment = false;
        $inquiry->testPricePayment = $inquiry->testPrice;
        $inquiry->analysisCostPayment = $inquiry->analysisCost;

        $request->session()->put('inquiry', $inquiry);

        return redirect(LocaleRouteFacade::route('kit.order'))->with('invoice-id', $invoice->paypal_invoice_id);
    }

    public function orderSuccess(Request $request)
    {
        $kit = $request->session()->get('kit');
        $invoice = $request->session()->get('invoice');

        return view('kits.order', [ 'kit' => $kit, 'invoice' => $invoice ]);
    }
}
