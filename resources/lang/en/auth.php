<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    '2fa' => array(
        'intro' => 'Please setup / verify Two-Factor-Auth continue.',
      'invalid' => 'The onetime-password is incorrect.',
    ),

    'redirect' => array(
      'userkey' => 'You have been logged out due to a missing userkey-cookie.',
    ),

];
