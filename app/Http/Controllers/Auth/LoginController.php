<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use DecryptsUserkey;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'verify2fa';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    public function logout(Request $request)
    {
        \Cookie::queue(\Cookie::forget(config('session.userkey_cookie')));

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/login');
    }

    protected function authenticated(Request $request, User $user)
    {
        $this->decryptKeyAndSetCookie($user, $request->get('password'));
    }
}
