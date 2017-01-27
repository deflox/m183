<?php

/**
 * Library for giving functionality for a login process
 * as insecure as possible.
 *
 * @author Leo Rudin
 */

namespace App\Libraries;

use App\Accessor;

class AuthInsecure extends Accessor
{
    /**
     * Contains current error message.
     *
     * @var string
     */
    private $error = null;

    /**
     * Contains name of the session.
     *
     * @var string
     */
    private $sessionName = 'userInsecure';

    /**
     * Signs the user in. Returns true if process was successful.
     *
     * @param  $email
     * @param  $password
     * @return boolean
     */
    public function signIn($email, $password)
    {

    }
}