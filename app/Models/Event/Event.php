<?php namespace App\Models\Event;

/**
 * Class Video
 *
 * @author Anuj Jaha er.anujjaha@gmail.com
 */

use App\Models\BaseModel;
use App\Models\Event\Traits\Attribute\Attribute;
use App\Models\Event\Traits\Relationship\Relationship;

class Event extends BaseModel
{
    use Attribute, Relationship;
    /**
     * Database Table
     *
     */
    protected $table = "events";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'name',
        'user_id',
        'title',
        'creator_id',
        'start_date',
        'end_date'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    /**
     * Set Images (JSON)
     *
     * @param  string  $value
     * @return string
     * /
    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = json_encode($value);
    }

    /**
     * Get Images (JSON)
     *
     * @param  string  $value
     * @return string
     * /
    public function getImagesAttribute($value)
    {
        return json_decode($value);
    }*/
}