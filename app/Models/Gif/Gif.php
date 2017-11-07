<?php namespace App\Models\Gif;

/**
 * Class Gif
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\Gif\Traits\Attribute\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gif extends BaseModel
{
    use Attribute, SoftDeletes;
    
    /**
     * Database Table
     *
     */
    protected $table = "data_gif";

    /**
     * Fillable Database Fields
     *
     */
    protected $fillable = [
        'gif'
    ];

    /**
     * Guarded ID Column
     *
     */
    protected $guarded = ["id"];

    public $timestamps = true;

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}   