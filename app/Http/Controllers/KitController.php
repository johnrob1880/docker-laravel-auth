<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

use App\User;
use App\KitRegistration;
use App\Invoice;
use App\Helpers\Contracts\OmegaWebApiContract;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kits.new');
    }

    public function invalid(Request $request, $barcode)
    {
        $inquiry = $request->session()->get('inquiry');

        return view('kits.invalid', [
            'barcode' => $barcode
        ]);
    }

    public function payment(Request $request, $barcode)
    {
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');

        if (is_null($kit) || is_null($inquiry))
        {
            return redirect()->route('kit.new');
        }

        $testPrice = $inquiry->testPriceDue;
        $analysisCost = $inquiry->analysisCostDue;
        $collectTestPrice = !$inquiry->isTestPricePaid;
        $upgradeTestId = '';
        
        if (!is_null($upgrade))
        {
            $testPrice = $upgrade->testPrice - $inquiry->testPricePayment;
            $collectTestPrice = $testPrice > 0;
            $upgradeTestId = $upgrade->testId;
        }

        return view('kits.payment', [
            'kit' => $kit,
            'barcode' => $barcode,
            'upgrade' => $upgrade,
            'testPrice' => $testPrice,
            'analysisCost' => $inquiry->analysisCost - $inquiry->analysisCostPayment,
            'collectAnalysisCost' => !$inquiry->isAnalysisCostPaid,
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

        return redirect()->route('kit.payment', ['barcode' => $barcode]);
    }

    public function upgradePost(Request $request, $barcode)
    {
        $request->validate(['testId' => 'required']);

        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');

        $testId = $request->input('testId');
        $testName = $request->input('test');

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
            return redirect()->route('kit.new');
        }


        $request->session()->put('upgrade', $selected);

        //$request->session()->flash('alert-success', 'Upgraded to ' . $testName . '!');

        return redirect()->route('kit.payment', ['barcode' => $barcode]);

    }

    public function upgrade(Request $request, $barcode) {

        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');

        if (is_null($kit) || is_null($inquiry))
        {
            return redirect()->route('kit.new');
        }
       
        return view('kits.upgrade', [
            'kit' => $kit,
            'barcode' => $barcode,
            'test' => $inquiry->test,
            'upgrade' => $upgrade,
            'upgrades' => $inquiry->testUpgrades
        ]);
    }

    public function cancelPost(Request $request, $barcode) {
        
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');
        $upgrade = $request->session()->get('upgrade');

        if (is_null($kit))
        {
            return redirect()->route('kit.new');
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
            return redirect()->route('kit.new');
        }

        $invoices = Invoice::where('kit_registration_id', $id)->count();

        if ($invoices > 0)
        {
            return redirect()->route('kit.new');
        }

        KitRegistration::destroy($current->id);

        $request->flash('alert-success', 'Kit was removed!');

        return redirect()->route('kit.new');
    }

    public function barcode(Request $request) 
    {

        $request->validate(['barcode' => 'required']);
        $user = Auth::user();
    
        $barcode = $request->input('barcode');

        $kit = KitRegistration::where('barcode', $barcode)->first();

        if (!is_null($kit)) {
            if ($kit->user_id != $user->id) {
                return view('kits.new')->withErrors(['barcode' => Lang::get('registration.kit-already-registered')]);
            }

            if ($kit->current_step == 0) {
                return view('kits.new')->withErrors(['barcode' => Lang::get('registration.kit-already-registered')]);
            }
        }

        // Validate kit 
        $inquiry = $this->api->getBarcodeInquiry($barcode);

        if ($inquiry == null || $inquiry->barcodeValue == null)
        {
            return view('kits.new')->withErrors(['barcode' => Lang::get('registration.invalid-barcode')]);
        }

        $request->session()->put('inquiry', $inquiry);

        if ($inquiry->linkedToPatient)
        {
            return view('kits.new')->withErrors(['barcode' => Lang::get('registration.kit-already-registered')]);
        }

        if (!$inquiry->linkedToTestPrice || !$inquiry->linkedToAnalysisCost)
        {
            return redirect()->route('kit.invalid', ['barcode' => $barcode]);
        }

        $kit = new KitRegistration(
            [
                'user_id' => $user->id, 
                'barcode' => $barcode, 
                'test_name' => $inquiry->test,
                'test_price' => $inquiry->testPrice,
                'current_step' => 0,
                'is_complete' => false
            ]);
       

        $request->session()->put('kit', $kit);
        

        if ($inquiry->canUpgrade)
        {
            return redirect()->route('kit.upgrade', ['barcode' => $barcode]);
        }

        // check if payment needed  
        if ($inquiry->requiresPayment) 
        {
            return redirect()->route('kit.payment', [ 'barcode' => $barcode ]);
        }
        
        return redirect()->route('kit.verify', [ 'barcode' => $barcode ]);
    }

    public function verify(Request $request, $barcode) 
    {
        $kit = $request->session()->get('kit');
        $inquiry = $request->session()->get('inquiry');

        if (is_null($kit) || is_null($inquiry))
        {
            return redirect()->route('kit.new');
        }

        if ($inquiry->requiresPayment)
        {
            $request->session()->flash('alert-danger', Lang::get('registration.payment-required'));
            return redirect()->route('kit.payment', [ 'barcode' => $barcode ]);
        }


        return view('kits.verify', [ 'kit' => $kit ]);
    }
}
