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
Route::group(['middleware' => ['auth', \App\Http\Middleware\HandlesUserkey::class]], function () {
    Route::get('verify2fa', 'Auth\Verify2FAController@show2FA');
    Route::post('verify2fa', 'Auth\Verify2FAController@verify2FA');
});

Route::group(['middleware' => ['auth', \App\Http\Middleware\HandlesUserkey::class, \App\Http\Middleware\Enforce2FAValidation::class]], function () {
    Route::group(['prefix' => 'api'], function () {
        Route::get('/user', function () {
            return auth()->user();
        });

        Route::get('/todos', 'TodoController@index');
        Route::post('/todos', 'TodoController@store');
        Route::match(['patch', 'put'], '/todos/{todo}', 'TodoController@update');
        Route::delete('/todos/{todo}', 'TodoController@destroy');
    });

    Route::get('/', function () {
        return view('home');
    })->name('home');
});

// TODO: CSP
