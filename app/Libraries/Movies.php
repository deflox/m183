<?php

/**
 * Library for doing all movie related stuff.
 *
 * @author Leo Rudin
 */

namespace App\Libraries;

use App\Accessor;
use App\Models\Movie;

class Movies extends Accessor
{
    /**
     * Contains current error message.
     *
     * @var string
     */
    private $error = null;

    /**
     * Adds a new movie to the database.
     *
     * @param  $data
     * @param  $userId
     * @return boolean
     */
    public function addMovie($data, $userId)
    {
        $movie = new Movie();

        $movie->user_id = $userId;
        $movie->imdb_id = $data['imdb_id'];
        $movie->title = $data['title'];
        $movie->year = $data['year'];
        $movie->genres = $data['genres'];
        $movie->runtime = $data['runtime'];
        $movie->plot = $data['plot'];
        $movie->image_url = $data['image_url'];

        if (!$movie->save()) {
            $this->error = "Unknown error occured";
            $this->logger->logValErr($this->error(), get_class($this), __FUNCTION__, __LINE__);
            return false;
        }

        return true;
    }

    /**
     * Gets all movies for the user.
     *
     * @param  $userId
     * @return array
     */
    public function getMoviesForUser($userId)
    {
        return Movie::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Deletes a movie.
     *
     * @param  $userId
     * @param  $movieId
     * @return boolean
     */
    public function deleteSecure($movieId, $userId)
    {
        $movie = Movie::where('id', $movieId)
            ->where('user_id', $userId)
            ->get()
            ->first();

        if (!isset($movie)) {
            $this->error = "This movie does not belong to you.";
            $this->logger->logValErr($this->error(), get_class($this), __FUNCTION__, __LINE__);
            return false;
        }

        Movie::destroy($movieId);
        return true;
    }

    /**
     * Deletes a movie insecure.
     *
     * @param  $movieId
     * @return boolean
     */
    public function deleteInsecure($movieId)
    {
        Movie::destroy($movieId);
        return true;
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