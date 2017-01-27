<?php

/**
 * Controller for handling movies.
 *
 * @author Leo Rudin
 */

namespace App\Controller;

use App\Accessor;

class MovieSecureController extends Accessor
{
    /**
     * Displays the index page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getMoviesSecure($req, $res)
    {
        return $this->view->render($res, 'moviesSecure.twig', [
            'movies' => $this->movies->getMoviesForUser($this->authSecure->getUserIdFromSession())
        ]);
    }

    /**
     * Displays the index page.
     *
     * @param  $req
     * @param  $res
     * @return \Slim\Views\Twig
     */
    public function getMoviesInsecure($req, $res)
    {
        return $this->view->render($res, 'moviesInsecure.twig', [
            'movies' => $this->movies->getMoviesForUser($this->authSecure->getUserIdFromSession())
        ]);
    }

    /**
     * Handles post request for adding movie.
     *
     * @param  $req
     * @param  $res
     * @return mixed
     */
    public function addMovie($req, $res)
    {
        $validation = $this->validation->validate($req, [
            'imdb_id|IMDb Id' => ['required'],
            'title|Title' => ['required', ['lengthMax', 255]],
            'year|Year' => ['required', 'integer', ['length', 4]],
            'runtime|Runtime' => ['required', 'integer'],
            'genres|Genres' => ['required'],
            'plot|Plot' => ['required', ['lengthMax', 1000]]
        ]);

        if ($validation->failed())
            return $this->api->createErrorResponse($res, "There are validation errors.", $this->validation->errors());

        if (!$this->movies->addMovie($req->getParams(), $this->authSecure->getUserIdFromSession()))
            return $this->api->createErrorResponse($res, $this->movies->error());

        return $this->api->createResponse($res);
    }


    /**
     * Handles post request for deleting a movie.
     *
     * @param  $req
     * @param  $res
     * @param  $args
     * @return mixed
     */
    public function deleteMovieSecure($req, $res, $args)
    {
        if (!$this->movies->deleteSecure($args['id'], $this->authSecure->getUserIdFromSession())) {
            return $this->api->createErrorResponse($res, $this->movies->error());
        } else {
            return $this->api->createResponse($res);
        }
    }

    /**
     * Handles post request for deleting a movie.
     *
     * @param  $req
     * @param  $res
     * @param  $args
     * @return mixed
     */
    public function deleteMovieInsecure($req, $res, $args)
    {
        if (!$this->movies->deleteInsecure($args['id'])) {
            return $this->api->createErrorResponse($res, $this->movies->error());
        } else {
            return $this->api->createResponse($res);
        }
    }
}