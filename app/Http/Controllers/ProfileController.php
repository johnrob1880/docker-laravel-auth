<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\User;
use App\Facades\LocaleRouteFacade;

use Config;

class ProfileController extends Controller
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
    public function showProfileEditForm(Request $request)
    {
        return view('profile.edit');
    }

    public function editPost(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) 
        {
            return view('profile.edit')->withErrors($validator);
        }

        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $date_of_birth = $request->input('date_of_birth');

        $user = Auth::user();

        if ($firstname == $user->firstname &&
            $lastname == $user->lastname &&
            $date_of_birth == $user->date_of_birth)
        {
            // No changes made
            return redirect(LocaleRouteFacade::route('home'));       
        }

        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->date_of_birth = $date_of_birth;
        $user->save();

        // dispatch patient update??

        return redirect(LocaleRouteFacade::route('home'))->with('alert-success', Lang::get('forms.phrases.profile-updated'));

        
    }
}
