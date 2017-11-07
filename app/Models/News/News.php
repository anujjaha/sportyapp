<?php namespace App\Models\News;

/**
 * Class News
 *
 * @author Anuj Jaha er.anujjaha@gmail.com
 */

use App\Models\BaseModel;
use App\Models\News\Traits\Attribute\Attribute;
use App\Models\News\Traits\Relationship\Relationship;

class News extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_news";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'news',
        'status'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];
}