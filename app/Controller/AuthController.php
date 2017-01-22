<?php

/**
 * Controller for handling authentication.
 *
 * @author Leo Rudin
 */

namespace App\Controller;

use App\Accessor;
use App\Models\User;

class AuthController extends Accessor
{
    /**
     * Displays the sign in page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getSignIn($req, $res)
    {
        return $this->view->render($res, 'auth/signin.twig');
    }

    /**
     * Handles post request for signing in.
     *
     * @param  $req
     * @param  $res
     * @return mixed
     */
    public function postSignIn($req, $res)
    {
        $validation = $this->validation->validate($req, [
            'email|E-Mail' => ['required', 'email'],
            'password|Password' => ['required']
        ]);

        if ($validation->failed())
            return $res->withRedirect($this->router->pathFor('auth-sign-in'));

        if (!$this->authSecure->signIn($req->getParam('email'), $req->getParam('password'), $req->getAttribute('ip_address'))) {
            $this->flash->addMessage('error', $this->authSecure->error());
            return $res->withRedirect($this->router->pathFor('auth-sign-in'));
        }

        return $res->withRedirect($this->router->pathFor('admin-dashboard'));
    }

    /**
     * Displays the sign up page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getSignUp($req, $res)
    {
        return $this->view->render($res, 'auth/signup.twig');
    }

    /**
     * Handles post request for signing up.
     *
     * @param  $req
     * @param  $res
     * @return mixed
     */
    public function postSignUp($req, $res)
    {
        \Valitron\Validator::addRule('check_alpha', function($field, $value) {
            return (preg_match("/^[A-Za-zöäüàéèê-]+$/", $value) === 1);
        }, 'darf nur alphabetische Zeichen enthalten sowie ä,ö,ü,à,é,è,ê und -.');

        $validation = $this->validation->validate($req, [
            'name|Name' => ['required', ['lengthMax', 75], 'check_alpha'],
            'email|E-Mail' => ['required', 'email', ['lengthMax', 75]],
            'password|Password' => ['required', ['equals', 'password_repeat']],
            'password_repeat|Repeated Password' => ['required']
        ]);

        if ($validation->failed())
            return $res->withRedirect($this->router->pathFor('auth-sign-up'));

        if (!$this->authSecure->signUp($req->getParams())) {
            $this->flash->addMessage('error', $this->authSecure->error());
            return $res->withRedirect($this->router->pathFor('auth-sign-up'));
        }

        $this->flash->addMessage('success', 'Signed up successfully.');
        return $res->withRedirect($this->router->pathFor('auth-sign-in'));
    }

    /**
     * Displays the sign in page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getForgotPassword($req, $res)
    {
        return $this->view->render($res, 'auth/forgot.twig');
    }

    /**
     * Handles post request for password forgot process.
     *
     * @param  $req
     * @param  $res
     * @return mixed
     */
    public function postForgotPassword($req, $res)
    {
        $validation = $this->validation->validate($req, [
            'forgot_email|E-Mail' => ['required', 'email']
        ]);

        if ($validation->failed())
            return $res->withRedirect($this->router->pathFor('auth-forgot-password'));

        $user = User::where('email', $req->getParam('forgot_email'))
            ->get()
            ->first();

        if (!isset($user)) {
            $this->flash->addMessage('error', 'There is no user with the given email.');
            return $res->withRedirect($this->router->pathFor('auth-forgot-password'));
        }

        $token = $this->authSecure->createForgotPasswordToken($user->id, $req->getParam('forgot_email'));

        if ($token === '') {
            $this->flash->addMessage('error', $this->authSecure->error());
            return $res->withRedirect($this->router->pathFor('auth-forgot-password'));
        }

        $mail = $this->mail->init([
            'to' => $req->getParam('forgot_email'),
            'subject' => 'thvm.ch - Passwort vergessen',
            'content' => $this->view->fetch('email/forgot.email.twig', [
                'token' => $token,
                'user' => $user,
            ])
        ]);

        if (!$mail->send()) {
            $this->flash->addMessage('error', 'An unknown error occurred. Please try again later.');
            return $res->withRedirect($this->router->pathFor('auth-forgot-password'));
        }

        $this->flash->addMessage('success', 'An unknown error occurred. Please try again later.');
        return $res->withRedirect($this->router->pathFor('auth-sign-in'));
    }

    /**
     * Displays the reset password page in case token is valid.
     *
     * @param  $req
     * @param  $res
     * @param  $args
     * @return mixed
     */
    public function getResetPassword($req, $res, $args)
    {
        if (!$this->authSecure->checkToken($args['token'])) {
            $this->flash->addMessage('error', $this->authSecure->error());
            return $res->withRedirect($this->router->pathFor('auth-sign-in'));
        }

        return $this->view->render($res, 'auth/reset.twig', [
            'token' => $args['token']
        ]);
    }

    /**
     * Handles post request for password reset process.
     *
     * @param  $req
     * @param  $res
     * @return mixed
     */
    public function postResetPassword($req, $res, $args)
    {
        if (!$this->authSecure->checkToken($args['token'])) {
            $this->flash->addMessage('error', $this->authSecure->error());
            return $res->withRedirect($this->router->pathFor('auth-sign-in'));
        }

        $validation = $this->validation->validate($req, [
            'password|New Password' => ['required', ['equals', 'password_repeat']],
            'password_repeat|New repeated password' => ['required']
        ]);

        if ($validation->failed())
            return $res->withRedirect($this->router->pathFor('auth-reset-password', ['token' => $args['token']]));

        if (!$this->authSecure->resetPassword($args['token'], $req->getParam('password'))) {
            $this->flash->addMessage('error', $this->authSecure->error());
            return $res->withRedirect($this->router->pathFor('auth-reset-password', ['token' => $args['token']]));
        }

        $this->flash->addMessage('success', 'You changed your password successfully. You can now sign in.');
        return $res->withRedirect($this->router->pathFor('auth-sign-in'));
    }

    /**
     * Handles the sign out process.
     *
     * @param  $req
     * @param  $res
     * @return mixed
     */
    public function getSignOut($req, $res)
    {
        $this->authSecure->signOut();
        $this->flash->addMessage('success', 'Erfolgreich ausgeloggt.');
        return $res->withRedirect($this->router->pathFor('auth-sign-in'));
    }
}