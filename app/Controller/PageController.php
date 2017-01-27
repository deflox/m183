<?php

/**
 * Controller for basic pages with no further logic.
 *
 * @author Leo Rudin
 */

namespace App\Controller;

use App\Accessor;

class PageController extends Accessor
{
    /**
     * Displays the index page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getIndex($req, $res)
    {
        $this->logger->logMethod(get_class($this), __FUNCTION__, __LINE__);
        if (!$this->authSecure->isUserAuthenticated()) {
            return $res->withRedirect($this->router->pathFor('auth-secure-sign-in'));
        } else {
            return $res->withRedirect($this->router->pathFor('movies-secure'));
        }
    }

    /**
     * Displays the documentation page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getDocumentation($req, $res)
    {
        $this->logger->logMethod(get_class($this), __FUNCTION__, __LINE__);
        return $this->view->render($res, 'documentation.twig');
    }

    /**
     * Displays the journal page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getJournal($req, $res)
    {
        $this->logger->logMethod(get_class($this), __FUNCTION__, __LINE__);
        return $this->view->render($res, 'journal.twig');
    }
}