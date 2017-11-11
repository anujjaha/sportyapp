<?php namespace App\Models\ReportComment;

/**
 * Class News
 *
 * @author Anuj Jaha er.anujjaha@gmail.com
 */

use App\Models\BaseModel;
use App\Models\ReportComment\Traits\Attribute\Attribute;
use App\Models\ReportComment\Traits\Relationship\Relationship;

class ReportComment extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_report_comments";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'comment_id',
        'status'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];
}