<?php namespace App\Models\Post;

/**
 * Class Video
 *
 * @author Niraj Jani
 */

use App\Models\BaseModel;
use App\Models\Post\Traits\Attribute\Attribute;
use App\Models\Post\Traits\Relationship\Relationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use Attribute, Relationship, SoftDeletes;
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
        'video_image',
        'description',
        'is_image',
        'is_wowza',
        'is_game_post',
        'image_thumbnail',
        'game_id',
        'home_team_id',
        'away_team_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}