<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\User;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\LinkPatient;
use App\Http\Controllers\Controller;

use Config;
use App;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/password/create';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
 
        $validator = Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'terms' => 'boolean',
            'results_via_email' => 'boolean',
            'email' => 'required|string|email|max:255|unique:users',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);
        
        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'date_of_birth' => $data['date_of_birth'],
            'email' => $data['email'],
            'results_via_email' => array_key_exists('results_via_email', $data),
            'verified' => false,
            'password' => bcrypt(Config::get('auth.default_password')),
            'origin' => Config::get('webapi.default_country'),
            'locale' => App::getLocale()
        ]);

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registered(Request $request, $user)
    {
        dispatch(new SendWelcomeEmail($user));
        dispatch(new LinkPatient($user));
        

        return redirect($this->redirectPath());
    }
}
