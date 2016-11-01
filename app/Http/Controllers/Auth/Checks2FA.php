<?php

namespace App\Http\Controllers\Auth;


use Defuse\Crypto\Crypto;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

trait Checks2FA
{
    use ValidatesRequests;

    protected $totpSecret;

    public function getSecret(Request $request)
    {
        if (!$this->totpSecret) {
            $this->totpSecret = Crypto::decrypt(auth()->user()->totp_secret, $request->attributes->get('userkey'));
        }

        return $this->totpSecret;
    }

    protected function validateOTP(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required|integer|between:1,999999',
        ]);
    }
}
