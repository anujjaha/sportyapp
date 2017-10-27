<?php namespace App\Models\FanChallenge;

/**
 * Class FanChallenge
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\FanChallenge\Traits\Relationship\Relationship;

class FanChallenge extends BaseModel
{
    use  Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_fan_challenge";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'home_team_id',
        'away_team_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = true;

}