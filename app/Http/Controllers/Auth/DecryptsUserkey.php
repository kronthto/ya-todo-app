<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Defuse\Crypto\KeyProtectedByPassword;

trait DecryptsUserkey
{
    /**
     * Decrypts the user's key and sets it to a cookie.
     *
     * @param User   $user
     * @param string $plainPassword
     */
    public function decryptKeyAndSetCookie(User $user, $plainPassword)
    {
        $keyPassphrase = User::getKeyPassword($user, $plainPassword);

        $this->setUserkeyCookie(KeyProtectedByPassword::loadFromAsciiSafeString(decrypt($user->user_key))->unlockKey($keyPassphrase)->saveToAsciiSafeString());
    }

    public function setUserkeyCookie($key)
    {
        \Cookie::queue(cookie(
            config('session.userkey_cookie'),
            $key,
            config('session.lifetime'),
            null,
            null,
            config('session.secure'),
            true
        ));
    }
}
