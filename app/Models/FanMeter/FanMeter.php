<?php namespace App\Models\FanMeter;

/**
 * Class FanMeter
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\FanMeter\Traits\Relationship\Relationship;

class FanMeter extends BaseModel
{
    use  Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_fan_meter";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'home_team_id',
        'away_team_id',
        'follow_team'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = true;

}