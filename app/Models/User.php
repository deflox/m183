<?php

/**
 * Model class representing the users table.
 *
 * @author Leo Rudin
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Get the tokens for the user.
     */
    public function tokens()
    {
        return $this->hasMany('App\Models\Token');
    }
}