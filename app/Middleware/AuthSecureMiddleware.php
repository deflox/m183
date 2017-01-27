<?php

/**
 * Middleware for checking if user is authenticated.
 *
 * @author Leo Rudin
 */

namespace App\Middleware;

use App\Accessor;

class AuthSecureMiddleware extends Accessor
{
    public function __invoke($req, $res, $next)
    {
        if (!$this->authSecure->isUserAuthenticated()) {
            $this->flash->addMessage('error', 'Please log in to gain access to this site.');
            return $res->withRedirect($this->router->pathFor('auth-secure-sign-in'));
        }

        return $next($req, $res);
    }
}