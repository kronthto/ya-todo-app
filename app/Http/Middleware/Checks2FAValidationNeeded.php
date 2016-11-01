<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

trait Checks2FAValidationNeeded
{
    protected function needs2FAValidation(Request $request)
    {
        return !\Auth::user()->verified_2fa || !$request->session()->get(Enforce2FAValidation::G2FA_AUTHORIZED);
    }
}
