<?php namespace App\Models\Post;

/**
 * Class Video
 *
 * @author Niraj Jani
 */

use App\Models\BaseModel;
use App\Models\Post\Traits\Attribute\Attribute;
use App\Models\Post\Traits\Relationship\Relationship;

class Post extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "posts";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'image',
        'description',
        'is_image',
        'is_wowza',
        'game_id',
        'home_team_id',
        'away_team_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

}