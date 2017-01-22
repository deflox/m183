<?php

/**
 * Model class representing the insecure users table.
 *
 * @author Leo Rudin
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInsecure extends Model
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
    protected $table = 'usersinsecure';
}