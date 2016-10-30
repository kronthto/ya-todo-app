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

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

// 2FA Routes...
Route::group(['middleware' => 'auth'], function () {

    Route::get('verify2fa', 'Auth\Verify2FAController@show2FA');

});

Route::group(['middleware' => ['auth', \App\Http\Middleware\Enforce2FAValidation::class]], function () { // TODO: Prolonginate USERKEY / Logout if non existent (every controller)

    Route::group(['prefix' => 'api'], function () {

        Route::get('/user', function () {
            return auth()->user();
        });

    });

    Route::get('/', function () {
       return view('home');
    });

});
