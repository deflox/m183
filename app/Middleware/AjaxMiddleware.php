<?php

/**
 * Validation for checking if ajax requests are valid.
 *
 * @author Leo Rudin
 */

namespace App\Middleware;

use App\Accessor;

class AjaxMiddleware extends Accessor
{
    public function __invoke($req, $res, $next)
    {
        if (!$req->isXhr())
            return $this->api->createErrorResponse($res, 'Not valid ajax request.');

        if (!$this->authSecure->isUserAuthenticated())
            return $this->api->createErrorResponse($res, 'session_expired');

        $response = $next($req, $res);
        return $response;
    }
}