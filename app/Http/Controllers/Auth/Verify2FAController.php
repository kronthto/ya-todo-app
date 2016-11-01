<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Verify2FAController extends Controller
{
    use Checks2FA;

    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            if (\Auth::user()->verified_2fa) {
                return redirect('/');
            }

            return $next($request);
        });
    }

    public function show2FA(Request $request)
    {
        $user = auth()->user();

        return view('auth.2fa', [
            'qrcode' => \Google2FA::getQRCodeInline(
                config('app.name'),
                $user->username,
                $this->getSecret($request)
            ),
        ]);
    }

    public function verify2FA(Request $request)
    {
        $this->validateOTP($request);

        /** @var User $user */
        $user = auth()->user();

        if (\Google2FA::verifyKey($this->getSecret($request), $request->get('otp'))) {
            $user->verified_2fa = true;
            $user->save();

            return redirect()->intended('/');
        }

        /** @var View $view */
        $view = $this->show2FA($request);

        return $view->withErrors(['otp' => \Lang::get('auth.invalidotp')]);
    }
}
