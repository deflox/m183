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
        return $this->view->render($res, 'index.twig');
    }
}