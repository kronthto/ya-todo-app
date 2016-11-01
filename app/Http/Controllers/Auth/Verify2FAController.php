<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Checks2FAValidationNeeded;
use App\Http\Middleware\Enforce2FAValidation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Verify2FAController extends Controller
{
    use Checks2FA, Checks2FAValidationNeeded;

    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            if (!$this->needs2FAValidation($request)) {
                return redirect('/');
            }

            return $next($request);
        });
    }

    public function show2FA(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $qrcode = null;
        if (!$user->verified_2fa) {
            $qrcode = \Google2FA::getQRCodeInline(
                config('app.name'),
                $user->username,
                $this->getSecret($request)
            );
        }

        return view('auth.2fa', [
            'qrcode' => $qrcode,
        ]);
    }

    public function verify2FA(Request $request)
    {
        $this->validateOTP($request);

        /** @var User $user */
        $user = auth()->user();

        if (\Google2FA::verifyKey($this->getSecret($request), $request->get('otp'))) {
            if (!$user->verified_2fa) {
                $user->verified_2fa = true;
                $user->save();
            }

            $request->session()->put(Enforce2FAValidation::G2FA_AUTHORIZED, true);

            return redirect()->intended('/');
        }

        /** @var View $view */
        $view = $this->show2FA($request);

        return $view->withErrors(['otp' => \Lang::get('auth.2fa.invalid')]);
    }
}
