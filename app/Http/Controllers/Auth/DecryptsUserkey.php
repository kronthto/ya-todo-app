<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Defuse\Crypto\KeyProtectedByPassword;

trait DecryptsUserkey
{
    /**
     * Decrypts the user's key and sets it to a cookie
     *
     * @param User $user
     * @param string $plainPassword
     */
    public function decryptKeyAndSetCookie(User $user, $plainPassword)
    {
        \Cookie::queue(cookie(
            config('session.userkey_cookie'),
            KeyProtectedByPassword::loadFromAsciiSafeString(decrypt($user->user_key))->unlockKey($plainPassword)->saveToAsciiSafeString(),
            config('session.lifetime'),
            null,
            null,
            config('session.secure'),
            true
        ));
    }
}
