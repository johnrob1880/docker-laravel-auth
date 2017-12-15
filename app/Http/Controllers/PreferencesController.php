<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use App\Constants\Activities;
use App\Facades\LocaleRouteFacade;
use App\Jobs\UpdatePatientResult;
use App\Jobs\RecordActivity;
use App\KitRegistration;

use Config;
 
class PreferencesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditForm(Request $request)
    {
        return view('preferences.edit');
    }

    public function editPost(Request $request)
    {
        $user = Auth::user();
        $destination = $request->input('destination');

        if (is_null($destination) || empty($destination))
        {
            $destination = 0;
        } 
        else 
        {
            $destination = 1;
        }

        if ($destination == $user->results_via_email) {
            // No changes
            return redirect(LocaleRouteFacade::route('home'));
        }

        $user->results_via_email = $destination;
        $user->save();

        $kits = KitRegistration::where('user_id', $user->id)->get();

        foreach ($kits as $kit)
        {
            dispatch(new UpdatePatientResult($user, $kit->barcode, $destination == 1 ? 'email' : 'web'));
        }

        $subject = sprintf('Results By Email: %s', $destination == 1 ? 'True' : 'False');

        dispatch(new RecordActivity($request, $user, Activities::PREFERENCES_UPDATED, $subject));

        return redirect(LocaleRouteFacade::route('home'))->with('alert-success', Lang::get('forms.phrases.preferences-updated'));

    }
}
