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

Route::get('/',  'WelcomeController@index')->name('welcome');

Auth::routes();

Route::post('/', 'Auth\RegisterController@register');

Route::get('/registered', 'Auth\RegisterController@registered')->name('registered');
Route::get('/terms-conditions', 'Auth\RegisterController@terms')->name('terms-conditions');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');

Route::get('password/create', 'Auth\CreatePasswordController@showCreateForm')->name('password.create');

Route::post('password/create', 'Auth\CreatePasswordController@create');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile/edit', 'ProfileController@showProfileEditForm')->name('profile.edit');

Route::get('/preferences/edit', 'PreferencesController@showEditForm')->name('preferences.edit');

Route::get('/kit/new', 'KitController@index')->name('kit.new');
Route::get('/kit/upgrade/{barcode}', 'KitController@upgrade')->name('kit.upgrade');

Route::get('/kit/payment/{barcode}', 'KitController@payment')->name('kit.payment');
Route::get('/kit/order', 'PaypalController@orderSuccess')->name('kit.order');
Route::get('/kit/verify', 'KitController@verify')->name('kit.verify');
Route::get('/kit/invalid/{barcode}', 'KitController@invalid')->name('kit.invalid');
Route::get('/kit/instructions/{name}', 'KitController@instructions')->name('kit.instructions');
Route::get('/kit/view/{id}', 'KitController@view')->name('kit.view');
Route::get('/kit/continue/{id}', 'KitController@continue')->name('kit.continue');
Route::get('/kit/mail', 'KitController@mail')->name('kit.mail');


Route::post('/kit/new', 'KitController@barcode');
Route::post('/kit/upgrade/{barcode}', 'KitController@upgradePost');
Route::post('/kit/cancel', 'KitController@cancelPost')->name('kit.cancel');
Route::post('/kit/cancel-upgrade', 'KitController@cancelUpgrade')->name('upgrade.cancel');
Route::post('/kit/verify', 'KitController@verifyPost');
Route::post('/kit/remove', 'KitController@removePost');
Route::post('/profile/edit', 'ProfileController@editPost');
Route::post('/preferences/edit', 'PreferencesController@editPost');

Route::get('paypal/express-checkout', 'PaypalController@expressCheckout')->name('paypal.express-checkout');
Route::get('paypal/express-checkout-success', 'PaypalController@expressCheckoutSuccess');
Route::get('paypal/express-checkout-cancel', 'PaypalController@expressCheckoutCancel');
