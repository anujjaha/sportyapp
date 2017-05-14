<?php namespace App\Models\Post;

/**
 * Class Video
 *
 * @author Niraj Jani
 */

use App\Models\BaseModel;
use App\Models\Post\Traits\Attribute\Attribute;
use App\Models\Post\Traits\Relationship\Relationship;

class PostLike extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "post_likes";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'post_id',
        'user_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

}