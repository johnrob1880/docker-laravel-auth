<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\KitRegistration;

class HomeController extends Controller
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
    public function index()
    {
        $user = Auth::user();

        $kits = KitRegistration::where('user_id', $user->id)->get();

        return view('home', [
            'kits' => $kits
        ]);
    }
}
