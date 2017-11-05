<?php namespace App\Models\PostGif;

/**
 * Class PostGif
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\PostGif\Traits\Relationship\Relationship;
use App\Models\Gif\Gif;

class PostGif extends BaseModel
{
    use Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_posts_gifs";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'post_id',
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
        return $this->belongsTo(Gif::class);
    }

}