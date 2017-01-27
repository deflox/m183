<?php

/**
 * Library for giving functionality for a login process
 * as secure as possible.
 *
 * @author Leo Rudin
 */

namespace App\Libraries;

use App\Accessor;
use App\Models\User;
use App\Models\Attempt;
use App\Models\Token;

class AuthSecure extends Accessor
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
    private $sessionName = 'userSecure';

    /**
     * Defines how many attempts a user can do before he gets locked out.
     *
     * @var int
     */
    private $attempts = 3;

    /**
     * Defines how long the user will be locked out of the application.
     *
     * @var int
     */
    private $lockTime = 5;

    /**
     * Signs the user in. Returns true if process was successful.
     *
     * @param  $email
     * @param  $password
     * @param  $ip
     * @return boolean
     */
    public function signIn($email, $password, $ip)
    {
        if ($this->checkAttempt($ip)) {

            $user = User::where('email', $email)
                ->get()
                ->first();

            // Check if user exists
            if (!isset($user)) {
                $this->error = "Your credentials are not correct. Please try again.";
                return false;
            }

            // Check password
            if (!password_verify($password, $user->password)) {
                $this->error = "Your credentials are not correct. Please try again.";
                return false;
            }

            // Set session
            $_SESSION[$this->sessionName] = [
                'id' => $user->id,
                'name' => $user->name,
            ];

            // Reset attempt if user logged in correctly
            $this->resetAttempt($ip);

            return true;

        } else {

            $this->error = "There were already to many failed login attempts. Please wait ".$this->lockTime." minutes und try again.";
            return false;

        }
    }

    /**
     * Returns false if user has exceeded his attempts or is still locked out from
     * the application.
     *
     * @param  $ip
     * @return boolean
     */
    private function checkAttempt($ip)
    {
        $attempt = Attempt::where('ip_address', md5($ip))
            ->get()
            ->first();

        if (!isset($attempt)) {

            $attempt = new Attempt();
            $attempt->ip_address = md5($ip);
            $attempt->count = 1;
            $attempt->save();
            return true;

        } else {

            if ($attempt->count >= $this->attempts) {

                if (!isset($attempt->lock_time)) {

                    Attempt::where('id', $attempt->id)
                        ->update([
                            'lock_time' => date('Y-m-d H:i:s')
                        ]);

                    return false;

                } else if (!$this->checkTimeStampAge($attempt->lock_time, $this->lockTime)) {

                    return false;

                } else {

                    Attempt::where('id', $attempt->id)
                        ->update([
                            'count' => 1,
                            'lock_time' => null
                        ]);

                    return true;

                }

            } else {
                Attempt::where('id', $attempt->id)
                    ->update([
                        'count' => $attempt->count + 1
                    ]);
                return true;
            }

        }
    }

    /**
     * Resets an attempt for a given ip address.
     *
     * @param $ip
     */
    private function resetAttempt($ip)
    {
        Attempt::where('ip_address', md5($ip))
            ->update([
                'count' => 0,
                'lock_time' => null
            ]);
    }

    /**
     * Signs the user up. Returns true if process was successful.
     *
     * @param  $data
     * @return true
     */
    public function signUp($data)
    {
        $user = User::where('email', $data['email'])
            ->get()
            ->first();

        if (isset($user)) {
            $this->error = "There's already an user with the given email address.";
            return false;
        }

        $user = new User();

        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->name = $data['name'];

        if (!$user->save()) {
            $this->error = "An unknown error occured.";
            return false;
        }

        return true;
    }

    /**
     * Creates the reset password token to reset the password.
     *
     * @return boolean
     */
    public function createForgotPasswordToken($id)
    {
        $token = new Token();

        $token->token = bin2hex(random_bytes(32));;
        $token->user_id = $id;

        if (!$token->save()) {
            $this->error = "An unknown error occured.";
            return '';
        }

        return $token->token;
    }

    /**
     * Checks if the given token is valid.
     *
     * @param  $tokenValue
     * @return boolean
     */
    public function checkToken($tokenValue)
    {
        $token = Token::where('token', $tokenValue)
            ->get()
            ->first();

        if (!isset($token)) {
            $this->error = "This link is invalid or expired.";
            return false;
        }

        if ($this->checkTimeStampAge($token->created_at, 120)) {
            $this->deleteToken($token->id);
            $this->error = "This link is invalid or expired.";
            return false;
        }

        return true;
    }

    /**
     * Resets password for a user.
     *
     * @param  $tokenValue
     * @param  $newPassword
     * @return boolean
     */
    public function resetPassword($tokenValue, $newPassword)
    {
        $token = Token::where('token', $tokenValue)
            ->get()
            ->first();

        $update = User::where('id', $token->user_id)
            ->update([
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);

        if (!$update) {
            $this->error = "An unknown error occured.";
            return false;
        }

        if (!$this->deleteToken($token->id)) {
            $this->error = "An unknown error occured.";
            return false;
        }

        return true;
    }

    /**
     * Deletes an token based on its id.
     *
     * @param  $id
     * @return boolean
     */
    private function deleteToken($id)
    {
        return Token::destroy($id) > 0;
    }

    /**
     * Signs the user out.
     */
    public function signOut()
    {
        unset($_SESSION[$this->sessionName]);
    }

    /**
     * Returns users id from the session.
     *
     * @return string
     */
    public function getUserIdFromSession()
    {
        return $_SESSION[$this->sessionName]['id'];
    }

    /**
     * Returns true if user is authenticated.
     *
     * @return boolean
     */
    public function isUserAuthenticated()
    {
        return isset($_SESSION[$this->sessionName]);
    }

    /**
     * Returns true if lock time on database is older than the
     * defined time, a user gets locked out of the application.
     *
     * @param  $timestamp
     * @param  $time
     * @return bool
     */
    private function checkTimeStampAge($timestamp, $time)
    {
        return (strtotime($timestamp) <= strtotime('-'.$time.' minutes'));
    }

    /**
     * Returns error message.
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }
}