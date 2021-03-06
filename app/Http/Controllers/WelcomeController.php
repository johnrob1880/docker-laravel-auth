<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\User;
use Illuminate\Validation\ValidationException;
use App\Facades\LocaleRouteFacade;

use Config;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect(LocaleRouteFacade::route('home'));
        }
        
        return view('welcome');

        //return redirect()->route('register');
    }
}
