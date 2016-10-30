<?php
/**
 * Created by PhpStorm.
 * User: Kroniversal
 * Date: 30.10.2016
 * Time: 20:07
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Verify2FAController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            if (\Auth::user()->verified_2fa) {
                return redirect('/');
            }

            return $next($request);
        });
    }

    public function show2FA()
    {
        return view('auth.2fa', [
            'qrcode' => \Google2FA::getQRCodeInline(
                config('app.name'),
                auth()->user()->username,
                'SECRET' // TODO
            )
        ]);
    }

    public function verify2FA()
    {
        // TODO: Check code, set flag, redirect
    }
}
