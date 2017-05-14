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
        'description'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

}