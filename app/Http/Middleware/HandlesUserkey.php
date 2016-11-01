<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\DecryptsUserkey;
use Closure;
use Defuse\Crypto\Key;

class HandlesUserkey
{
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
            // TODO: Logout
        }

        $this->setUserkeyCookie($userkey);

        $request->attributes->set('userkey', Key::loadFromAsciiSafeString($userkey));

        return $next($request);
    }
}
