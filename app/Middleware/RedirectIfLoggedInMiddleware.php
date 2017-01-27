<?php

/**
 * Middleware for adding redirect user from login views in case he's logged in.
 *
 * @author Leo Rudin
 */

namespace App\Middleware;

use App\Accessor;

class RedirectIfLoggedInMiddleware extends Accessor
{
    public function __invoke($req, $res, $next)
    {
        if ($this->authSecure->isUserAuthenticated())
            return $res->withRedirect($this->router->pathFor('movies'));

        return $next($req, $res);
    }
}