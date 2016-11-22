<?php

namespace App;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User.
 *
 * @property int $id
 * @property string $username
 * @property string $user_key
 * @property string $totp_secret
 * @property bool $verified_2fa
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 */
class User extends Authenticatable
{
    const KEYPASS_HASH_ALGO = 'sha384';
    const PBKDF2_ITERATIONS = 1000;

    use Notifiable;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_2fa' => 'boolean',
    ];

    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
        // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }

    // Overrides the method to ignore the remember token.
    public function setAttribute($key, $value)
    {
        if ($key !== parent::getRememberTokenName()) {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'totp_secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'user_key',
        'totp_secret',
    ];

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    /**
     * Builds a (hopefully) secure secret derivated from the user password
     *
     * @param User $user
     * @param string $plainpass
     *
     * @return string
     */
    public static function getKeyPassword(User $user, $plainpass)
    {
        $userKey = $user->getKey();
        if (!$userKey) {
            throw new \InvalidArgumentException('User does not have an ID');
        }

        $salt = substr(hash_hmac(static::KEYPASS_HASH_ALGO, $userKey, app('encrypter')->getKey()), 0, 32);

        return base64_encode(hash_pbkdf2(static::KEYPASS_HASH_ALGO, $plainpass, $salt, static::PBKDF2_ITERATIONS, 32, true));
    }

    /**
     * Creates a user and all the necessary keys using data from the registration form.
     *
     * @param array $data
     *
     * @return User
     */
    public static function createByRegister(array $data)
    {
        $user = new User([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
        $user->verified_2fa = false;
        $user->save();

        $keyPassphrase = User::getKeyPassword($user, $data['password']);
        $userKey = KeyProtectedByPassword::createRandomPasswordProtectedKey($keyPassphrase);
        $user->user_key = encrypt($userKey->saveToAsciiSafeString());
        $user->totp_secret = Crypto::encrypt(\Google2FA::generateSecretKey(), $userKey->unlockKey($keyPassphrase));
        $user->save();

        return $user;
    }
}
