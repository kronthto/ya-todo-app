<?php

namespace App\Http\Middleware;

use Closure;

class Enforce2FAValidation
{
    const G2FA_AUTHORIZED = 'g2fa_authorized';

    use Checks2FAValidationNeeded;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->needs2FAValidation($request)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => '2FA not verified.'], 401);
            }

            return redirect()->guest('verify2fa');
        }

        return $next($request);
    }
}
