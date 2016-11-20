<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, DecryptsUserkey;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'verify2fa';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
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

    protected function registered(Request $request, $user)
    {
        $this->decryptKeyAndSetCookie($user, $request->get('password'));
    }
}
