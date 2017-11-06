<?php namespace App\Models\Location;

/**
 * Class Location
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;

class Location extends BaseModel
{
    
    /**
     * Database Table
     *
     */
    protected $table = "data_users_lat_long";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'user_id',
        'lat',
        'long'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = false;

    /**
     * Relationship Mapping for Account
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}