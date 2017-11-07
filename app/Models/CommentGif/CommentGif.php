<?php namespace App\Models\CommentGif;

/**
 * Class CommentGif
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\CommentGif\Traits\Relationship\Relationship;
use App\Models\Gif\Gif;
use App\Models\Post\PostComment;

class CommentGif extends BaseModel
{
    use Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_comments_gifs";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'comment_id',
        'gif_id'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = false;

    /**
     * Relationship Mapping for Post
     * @return mixed
     */
    public function gif()
    {
        return $this->belongsTo(Gif::class, 'gif_id');
    }

    /**
     * Relationship Mapping for Post
     * @return mixed
     */
    public function comment()
    {
        return $this->belongsTo(PostComment::class, 'comment_id');
    }

}