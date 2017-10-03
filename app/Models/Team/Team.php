<?php namespace App\Models\Team;

/**
 * Class Team
 *
 * @author Anuj Jaha er.anujjaha@gmail.com
 */

use App\Models\BaseModel;
use App\Models\Team\Traits\Attribute\Attribute;
use App\Models\Team\Traits\Relationship\Relationship;

class Team extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "data_teams";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'name',
        'location',
        'image',
        'status'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];
}