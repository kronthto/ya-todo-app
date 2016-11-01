<?php

namespace App\Http\Middleware;

use Closure;

class Enforce2FAValidation
{
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
        if (!\Auth::user()->verified_2fa) {
            if ($request->expectsJson()) {
                return response()->json(['error' => '2FA not verified.'], 403);
            }

            return redirect()->guest('verify2fa');
        }

        return $next($request);
    }
}
