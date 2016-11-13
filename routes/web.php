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

$api = app('Dingo\Api\Routing\Router'); // TODO: Key / 2FA Checks
$api->version('v1', ['middleware' => 'api.auth'], function (\Dingo\Api\Routing\Router $api) {
    $api->get('user', function() {
        return app('Dingo\Api\Auth\Auth')->user();
    });

    $api->get('todos', 'App\Http\Controllers\TodoController@index');
    $api->post('todos', 'App\Http\Controllers\TodoController@store');
    $api->match(['patch', 'put'], 'todos/{todo}', 'App\Http\Controllers\TodoController@update');
    $api->delete('todos/{todo}', 'App\Http\Controllers\TodoController@destroy');
});

Route::group(['middleware' => ['auth', \App\Http\Middleware\HandlesUserkey::class, \App\Http\Middleware\Enforce2FAValidation::class]], function () {
    Route::get('/', function () {
        return view('home', ['jwt' => JWTAuth::fromUser(auth()->user())]);
    })->name('home');
});

// TODO: CSP
