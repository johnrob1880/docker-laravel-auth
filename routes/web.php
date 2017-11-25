<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');

Auth::routes();

Route::get('/registered', 'Auth\RegisterController@registered')->name('registered');

Route::get('/signin', 'Auth\LoginController@showLoginForm')->name('signin');

Route::get('password/create', 'Auth\CreatePasswordController@showCreateForm')->name('password.create');

Route::post('password/create', 'Auth\CreatePasswordController@create');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/kit/new', 'KitController@index')->name('kit.new');
Route::get('/kit/upgrade/{barcode}', 'KitController@upgrade')->name('kit.upgrade');

Route::get('/kit/payment/{barcode}', 'KitController@payment')->name('kit.payment');
Route::get('/kit/verify/{barcode}', 'KitController@verify')->name('kit.verify');
Route::get('/kit/invalid/{barcode}', 'KitController@invalid')->name('kit.invalid');

Route::post('/kit/new', 'KitController@barcode');
Route::post('/kit/upgrade/{barcode}', 'KitController@upgradePost');
Route::post('/kit/cancel/{barcode}', 'KitController@cancelPost')->name('kit.cancel');
Route::post('/kit/cancel/upgrade', 'KitController@cancelUpgrade')->name('upgrade.cancel');

Route::get('paypal/express-checkout', 'PaypalController@expressCheckout')->name('paypal.express-checkout');
Route::get('paypal/express-checkout-success', 'PaypalController@expressCheckoutSuccess');
