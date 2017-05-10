<?php namespace App\Models\FollowUser;

/**
 * Class Video
 *
 * @author Niraj Jani
 */

use App\Models\BaseModel;
use App\Models\FollowUser\Traits\Attribute\Attribute;
use App\Models\FollowUser\Traits\Relationship\Relationship;

class FollowUser extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "follow_user";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'follower_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = false;

}