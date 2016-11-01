<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\DecryptsUserkey;
use Closure;
use Defuse\Crypto\Key;

class HandlesUserkey
{
    const USERKEY = 'userkey';

    use DecryptsUserkey;

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
        $userkey = $request->cookie(config('session.userkey_cookie'));

        if (!$userkey) {
            \Auth::logout();

            $request->session()->flush();

            $request->session()->regenerate();

            $request->session()->flash('login-reason', \Lang::get('auth.redirect.userkey'));

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Missing userkey. Your session has been terminated.'], 401);
            }

            return redirect()->guest('login');
        }

        $this->setUserkeyCookie($userkey);

        $request->attributes->set(self::USERKEY, Key::loadFromAsciiSafeString($userkey));

        return $next($request);
    }
}
