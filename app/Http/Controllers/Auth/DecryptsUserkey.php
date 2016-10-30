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
            env('USER_KEY_COOKIE_NAME'),
            KeyProtectedByPassword::loadFromAsciiSafeString(decrypt($user->user_key))->unlockKey($plainPassword)->saveToAsciiSafeString(),
            env('SESSION_LIFETIME', 120),
            null,
            null,
            env('SESSION_SECURE_COOKIE', true),
            true
        ));
    }
}
