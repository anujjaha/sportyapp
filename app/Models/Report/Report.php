<?php namespace App\Models\Report;

/**
 * Class News
 *
 * @author Anuj Jaha er.anujjaha@gmail.com
 */

use App\Models\BaseModel;
use App\Models\Report\Traits\Attribute\Attribute;
use App\Models\Report\Traits\Relationship\Relationship;

class Report extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_report_posts";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'status'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];
}