<?php namespace App\Models\FollowTeam;

/**
 * Class FollowTeam
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\FollowTeam\Traits\Relationship\Relationship;

class FollowTeam extends BaseModel
{
    use  Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "follow_teams";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'team_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = true;

}