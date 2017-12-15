<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

use App\User;
use App\KitRegistration;
use App\Invoice;
use App\Helpers\Contracts\OmegaWebApiContract;
use App\Facades\LocaleRouteFacade;
use App\Jobs\CreatePatient;
use App\Jobs\CreatePatientTest;
use App\Jobs\LinkPatient;
use App\Jobs\ProcessUpgrade;
use App\Jobs\LinkUpgradeCost;

use Config;

class KitController extends Controller
{
    protected $client;
    protected $api;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OmegaWebApiContract $api)
    {
        $this->middleware('auth');

        $this->api = $api;
        $this->client = new \GuzzleHttp\Client(['base_uri' => Config::get('app.omegaquant_api')]);
    }


    public function view(Request $request, $origin = null, $locale = null, $id = null)
    {
        $user = Auth::user();
        $kit = KitRegistration::where([['user_id', '=', $user->id], ['id', '=', $id]])->first();

        if (is_null($kit))
        {
            return redirect(LocaleRouteFacade::route('home'))->with('alert-danger', 'Unable to find that kit!');
        }

        $inquiry = $this->api->getBarcodeInquiry($kit->barcode);

        $request->session()->put('inquiry', $inquiry);

        $invoices = Invoice::where('kit_registration_id', $kit->id)->get();

        $features = DB::table('features')
            ->join('product_features', 'features.id', '=', 'product_features.feature_id')
            ->join('products', 'product_features.product_id', '=', 'products.id')
            ->where('products.product_id', $kit->test_id)
            ->select('features.title')
            ->get();

        return view('kits.view', [
            'kit' => $kit,
            'inquiry' => $inquiry,
            'invoices' => $invoices,
            'features' => $features
        ]);

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kits.new', ['barcode' => '']);
    }

    public function invalid(Request $request, $origin = null, $locale = null, $barcode)
    {
        $inquiry = $request->session()->get('inquiry');

        return view('kits.invalid', [
            'barcode' => $barcode
        ]);
    }

    public function payment(Request $request, $origin = null, $locale = null, $barcode)
    {
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');

        if (is_null($kit) || is_null($inquiry))
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        $testPrice = 0;        
        $collectTestPrice = false;
        $upgradeTestId = '';
        
        if (!is_null($upgrade))
        {
            $testPrice = $upgrade->upgradePrice;
            $collectTestPrice = $upgrade->upgradePrice > 0;
            $upgradeTestId = $upgrade->testId;
        }

        // only WithKit billing methods are collected online
        $analysisCost = $inquiry->analysisCostDue;
        $collectAnalysisCost = $inquiry->billingMethod == "With Sample";

        if (!$collectAnalysisCost && is_null($upgrade))
        {
            $inquiry->requiresPayment = false;                                                                                                              
            $request->session()->put('inquiry', $inquiry);

            return redirect(LocaleRouteFacade::route('kit.verify'));
        }

        return view('kits.payment', [
            'kit' => $kit,
            'barcode' => $barcode,
            'upgrade' => $upgrade,
            'testPrice' => $testPrice,
            'analysisCost' => $collectAnalysisCost ? $inquiry->analysisCost - $inquiry->analysisCostPayment : 0,
            'collectAnalysisCost' => $collectAnalysisCost,
            'collectTestPrice' => $collectTestPrice
        ]);
    }

    public function cancelUpgrade(Request $request)
    {
        if ($request->session()->has('upgrade'))
        {
            $request->session()->forget('upgrade');
        }

        $barcode = $request->input('barcode');

        return redirect(LocaleRouteFacade::route('kit.payment', ['barcode' => $barcode]));
    }

    public function upgradePost(Request $request, $origin = null, $locale = null, $barcode)
    {
        $request->validate(['testId' => 'required']);

        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');

        $testId = $request->input('testId');

        $selected = null;

        foreach ($inquiry->testUpgrades as $upgrade)
        {
            if ($upgrade->testId == $testId)
            {
                $selected = $upgrade;
            }
        }

        if (is_null($selected))
        {
            $request->session()->flash('alert-danger', Lang::get('registration.error-try-again'));
            return redirect(LocaleRouteFacade::route('kit.new'));
        }


        $request->session()->put('upgrade', $selected);

        return redirect(LocaleRouteFacade::route('kit.payment', ['barcode' => $barcode]));

    }

    public function continue(Request $request, $origin = null, $locale = null, $id = null)
    {
        $user = Auth::user();
        $kit = KitRegistration::where([['user_id', '=', $user->id], ['id', '=', $id]])->first();

        if (is_null($kit))
        {
            return redirect(LocaleRouteFacade::route('home'))->with('alert-danger', Lang::get('registration.unable-to-continue'));
        }

        $inquiry = $this->api->getBarcodeInquiry($kit->barcode);

        if (is_null($inquiry) || is_null($inquiry->barcodeValue))
        {
            return redirect(LocaleRouteFacade::route('home'))->with('alert-danger', Lang::get('registration.unable-to-continue'));
        }

        $request->session()->put('kit', $kit);
        $request->session()->put('inquiry', $inquiry);

        if ($inquiry->canUpgrade)
        {
            return redirect(LocaleRouteFacade::route('kit.upgrade', ['barcode' => $inquiry->barcodeValue]));
        }

        // check if payment needed  
        if ($inquiry->requiresPayment && $inquiry->billingMethod == "With Sample") 
        {
            return redirect(LocaleRouteFacade::route('kit.payment', [ 'barcode' => $inquiry->barcodeValue ]));
        }
        
        return redirect(LocaleRouteFacade::route('kit.verify'));
    }

    public function removePost(Request $request)
    {
        $user = Auth::user();
        $kit_id = $request->input('kit_id');

        $kit = KitRegistration::where([['user_id', '=', $user->id], ['id', '=', $kit_id]])->first();

        if (is_null($kit))
        {
            return redirect(LocaleRouteFacade::route('home'));
        }

        if ($kit->is_complete) 
        {
            return redirect(LocaleRouteFacade::route('home'))->with('alert-danger', Lang::get('registration.unable-to-remove'));
        }

        $invoices = \App\Invoice::where([['user_id', '=', $user->id], ['kit_registration_id', '=', $kit->id]])->get();

        foreach ($invoices as $invoice)
        {
            if ($invoice->paid) {
                return redirect(LocaleRouteFacade::route('home'))->with('alert-danger', Lang::get('registration.unable-to-remove-invoices'));
            }
        }

        // Add try catch logging
        foreach ($invoices as $invoice)
        {
            \App\Invoice::destroy($invoice->id);
        }

        KitRegistration::destroy($kit->id);

        return redirect(LocaleRouteFacade::route('home'))->with('alert-info', Lang::get('registration.kit-removed'));
    }

    public function upgrade(Request $request, $origin = null, $locale = null, $barcode) {

        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');


        if (is_null($kit) || is_null($inquiry))
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        $products = \App\Product::where('product_group', 'Test Kits')->orderby('product_index')->get();
        $features = \App\Feature::where(['feature_category' => 'Products', 'feature_group' => 'Test Kits'])->get();
        
        foreach ($products as $product)
        {
            $product->selected = $product->product_id == $kit->test_id;

            if ($product->product_id == $kit->test_id)
            {
                $product->price = $kit->test_price;
            }
            else 
            {
                foreach ($inquiry->testUpgrades as $upgrade)
                {
                    if ($product->product_id == $upgrade->testId) {
                        $product->price = $upgrade->testPrice;
                    }
                }
            }

            $product->features = \App\ProductFeature::where('product_id', $product->id)->get();
        }

        return view('kits.upgrade', [
            'kit' => $kit,
            'barcode' => $barcode,
            'test' => $inquiry->test,
            'upgrade' => $upgrade,
            'upgrades' => $inquiry->testUpgrades,
            'products' => $products,
            'features' => $features
        ]);
    }

    public function cancelPost(Request $request) {
        
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');

        if (is_null($kit))
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        if (!is_null($inquiry)) 
        {
            $request->session()->forget('inquiry');
        }
        
        if (!is_null($upgrade))
        {
            $request->session()->forget('upgrade');
        }

        $id = $kit['id'];

        $request->session()->forget('kit');

        $current = KitRegistration::findOrFail($id);

        // Can't remove if the kit is complete or has invoices attached
        if ($current->is_complete || $kit->is_complete)
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        $invoices = Invoice::where('kit_registration_id', $id)->count();

        if ($invoices > 0)
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        KitRegistration::destroy($current->id);

        return redirect(LocaleRouteFacade::route('kit.new'));
    }

    public function barcode(Request $request) 
    {

        $request->validate(['barcode' => 'required']);
        $user = Auth::user();
    
        $barcode = $request->input('barcode');

        // Remove any existing upgrade
        if ($request->session()->has('upgrade'))
        {
            $request->session()->forget('upgrade');
        }


        // Validate kit 
        $inquiry = $this->api->getBarcodeInquiry($barcode);

        if ($inquiry == null || $inquiry->barcodeValue == null)
        {
            return view('kits.new', ['barcode' => $barcode])->withErrors(['barcode' => Lang::get('registration.invalid-barcode')]);
        }

        $kit = KitRegistration::where('barcode', $inquiry->barcodeValue)->first();

        if (!is_null($kit)) {

            if ($kit->user_id != $user->id) {
                return view('kits.new', ['barcode' => $barcode])->withErrors(['barcode' => Lang::get('registration.kit-already-registered')]);
            }

            if ($kit->is_complete) {
                return view('kits.new', ['barcode' => $barcode])->withErrors(['barcode' => Lang::get('registration.kit-already-registered')]);
            }
        }

        

        $request->session()->put('inquiry', $inquiry);

        if ($inquiry->linkedToPatient)
        {
            return view('kits.new', ['barcode' => $barcode])->withErrors(['barcode' => Lang::get('registration.kit-already-registered')]);
        }

        if (!$inquiry->linkedToAnalysisCost)
        {
            return view('kits.new', ['barcode' => $barcode])->withErrors(['barcode' => Lang::get('registration.invalid-barcode')]);
        }

        if (is_null($kit))
        {
            $kit = new KitRegistration(
                [
                    'user_id' => $user->id, 
                    'barcode' => $inquiry->barcodeValue,
                    'origin' => Config::get('app.origin'),
                    'test_name' => $inquiry->test,
                    'test_id' => $inquiry->testId,
                    'test_price' => $inquiry->testPrice,
                    'analysis_cost' => $inquiry->analysisCost,
                    'is_complete' => false
                ]);
        }
       

        $request->session()->put('kit', $kit);
        

        if ($inquiry->canUpgrade)
        {
            return redirect(LocaleRouteFacade::route('kit.upgrade', ['barcode' => $inquiry->barcodeValue]));
        }

        // check if payment needed  
        if ($inquiry->requiresPayment) 
        {
            return redirect(LocaleRouteFacade::route('kit.payment', [ 'barcode' => $inquiry->barcodeValue ]));
        }
        
        return redirect(LocaleRouteFacade::route('kit.verify'));
    }

    public function verifyPost(Request $request, $origin, $locale)
    {
        $user = Auth::user();
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');


        if (is_null($kit) || is_null($inquiry))
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }
       
        if (!is_null($request->input('edit')))
        {
            return view('kits.verify', [ 'kit' => $kit, 'edit_mode' => true ]);
        }

        // check  for edit information...
        if (!is_null($request->input('update')))
        {
            
            // validate
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
            ]);

            if ($validator->fails()) 
            {
                return view('kits.verify', [ 'kit' => $kit, 'edit_mode' => true ])->withErrors($validator);
            }

            
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->save();

            return view('kits.verify', [ 'kit' => $kit, 'edit_mode' => false ]);

        }

        $testId = is_null($upgrade) ? $inquiry->testId : $upgrade->testId;

        // Save kit
        $kit->is_complete = true;
        $kit->test_id = $testId;
        $kit->save();

        if (!$inquiry->linkedToPatient) 
        {
            dispatch(new CreatePatient($user, $inquiry->barcodeValue, $inquiry->accountId));
        }

        $linkPatientJob = (new LinkPatient($inquiry->barcodeValue, Auth::user()))->delay(60);
        $patientTestJob = (new CreatePatientTest($user, $inquiry->barcodeValue, $testId))->delay(60);
        
        dispatch($linkPatientJob);
        dispatch($patientTestJob);

        if (!is_null($upgrade))
        {
            $kit->upgraded_from_test_id = $inquiry->testId;
            $kit->save();

            dispatch(new ProcessUpgrade($user, $kit->barcode, $upgrade));
            dispatch(new LinkUpgradeCost($user, $kit->barcode, $upgrade));
        }

        $name = Lang::get('products.' . $testId . '.slug');

        return redirect(LocaleRouteFacade::route('kit.instructions', [ 'name' => $name]));

    }

    public function verify(Request $request, $origin, $locale) 
    {
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');

        if (is_null($kit) || is_null($inquiry))
        {
            return redirect(LocaleRouteFacade::route('kit.new'));
        }

        if ($inquiry->requiresPayment && $inquiry->billingMethod == "With Sample")
        {
            return redirect(LocaleRouteFacade::route('kit.payment', [ 'barcode' => $barcode ]))->with('alert-danger', Lang::get('registration.payment-required'));
        }


        return view('kits.verify', [ 'kit' => $kit, 'edit_mode' => false ]);
    }

    public function instructions(Request $request, $origin, $locale, $name)
    {
        $view_name = 'kits.instructions.' . $name;

        if (!view()->exists($view_name))
        {
            return redirect(LocaleRouteFacade::route('home'));
        }

        return view($view_name, []);
    }

    public function mail(Request $request, $origin, $locale)
    {

        return view('kits.mail', []);
    }
}
