<?php namespace App\Models\Post;

/**
 * Class Post Comment
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\Access\User\User;
use App\Models\Post\Post;
use App\Models\CommentGif\CommentGif;
#use App\Models\Post\Traits\Relationship\PostRelationship;

class PostComment extends BaseModel
{
//    use PostRelationship;
    /**
     * Database Table
     *
     */
    protected $table = "post_comments";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'comment'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = true;

    /**
     * Relationship Mapping for Account
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship Mapping for Account
     * @return mixed
     */
    public function post_detail()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Relationship Mapping for Account
     * @return mixed
     */
    public function comment_gif()
    {
        return $this->hasOne(CommentGif::class, 'comment_id');
    }
}