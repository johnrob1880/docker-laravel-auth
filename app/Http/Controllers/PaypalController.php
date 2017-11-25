<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\Invoice;

use Auth;

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
            $request->flash('alert-danger', 'Unable to continue!');
            return redirect()->route('home');
        }

        // Save the kit
        $kit->current_step = 2;
        $kit->save();

      
        // get new invoice id
        $invoice_id = Invoice::count() + 1;
            
        // Get the cart data
        $cart = $this->getCart($request, $invoice_id);
      
        // create new invoice
        $invoice = new Invoice();
        $invoice->title = $cart['invoice_description'];
        $invoice->price = $cart['total'];
        $invoice->user_id = $user->id;
        $invoice->kit_registration_id = $kit->id;
        $invoice->save();
      
        // send a request to paypal 
        // paypal should respond with an array of data
        // the array should contain a link to paypal's payment system
        $response = $this->provider->setExpressCheckout($cart, $recurring);
      
        // if there is no link redirect back with error message
        if (!$response['paypal_link']) {
            $request->flash('alert-danger', $response['L_LONGMESSAGE0']);
            return redirect()->route('home');
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

        $product = is_null($upgrade) ? $kit['test_name'] : $upgrade->test;
        $price = is_null($upgrade) ? $inquiry->testPriceDue : $inquiry->testPriceDue + $upgrade->upgradePrice;
        
          return [
              // if payment is not recurring cart can have many items
              // with name, price and quantity
              'items' => [
                  [
                      'name' => $product,
                      'price' => $price,
                      'qty' => 1,
                  ]
              ],
  
              // return url is the url where PayPal returns after user confirmed the payment
              'return_url' => url('/paypal/express-checkout-success'),
              // every invoice id must be unique, else you'll get an error from paypal
              'invoice_id' => config('paypal.invoice_prefix') . '_' . $invoice_id,
              'invoice_description' => "Order #" . $invoice_id . " " . $product,
              'cancel_url' => url('/'),
              // total is calculated by multiplying price with quantity of all cart items and then adding them up
              // in this case total is 20 because Product 1 costs 10 (price 10 * quantity 1) and Product 2 costs 10 (price 5 * quantity 2)
              'total' => $price,
          ];
      }

      public function expressCheckoutSuccess(Request $request) {
        
        $user = Auth::user();
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');

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
            $request->flash('alert-danger', 'Error processing PayPal payment');
            return redirect()->route('kit.payment', ['barcode' => $kit['barcode']]);
        }

        // invoice id is stored in INVNUM
        // because we set our invoice to be xxxx_id
        // we need to explode the string and get the second element of array
        // witch will be the id of the invoice
        $invoice_id = explode('_', $response['INVNUM'])[1];

        // get cart data
        $cart = $this->getCart($request, $invoice_id);

        // Perform transaction on PayPal
        // and get the payment status
        $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);

        if (array_key_exists($payment_status, 'PAYMENTINFO_0_PAYMENTSTATUS'))
        {
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
        } 

        // find invoice by id
        $invoice = Invoice::find($invoice_id);

        // set invoice status
        $invoice->payment_status = $status;

        // set user id
        $invoice->user_id = $user->id;

        // set kit id
        $invoice->kit_registration_id = $kit->id;
    
        // save the invoice
        $invoice->save();

        // App\Invoice has a paid attribute that returns true or false based on payment status
        // so if paid is false return with error, else return with success message
        if ($invoice->paid) {

            // upgrade the inquiry
            $inquiry->requiresPayment = false;
            $inquiry->testPricePayment = $inquiry->testPrice;
            $inquiry->analysisCostPayment = $inquiry->analysisCost;

            $request->session()->put('inquiry', $inquiry);

            $request->flash('alert-success', 'Order ' . $invoice->id . ' has been paid successfully!');

            return redirect()->route('kit.verify', ['barcode' => $kit['barcode']]);
        }
        
        $request->flash('alert-danger', 'Error processing PayPal payment for Order ' . $invoice->id . '!');
        return redirect()->route('kit.payment', ['barcode' => $kit['barcode']]);
    }
}
