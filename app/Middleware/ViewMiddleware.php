<?php

/**
 * Middleware for adding global values to twig view.
 *
 * @author Leo Rudin
 */

namespace App\Middleware;

use App\Accessor;

class ViewMiddleware extends Accessor
{
    public function __invoke($req, $res, $next)
    {
        $this->view->getEnvironment()->addGlobal('authSecureAuthenticated', $this->authSecure->isUserAuthenticated());

        $response = $next($req, $res);
        return $response;
    }
}