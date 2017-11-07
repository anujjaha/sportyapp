<?php namespace App\Models\Gif;

/**
 * Class Gif
 *
 * @author Anuj Jaha
 */

use App\Models\BaseModel;
use App\Models\Gif\Traits\Attribute\Attribute;

class Gif extends BaseModel
{
    use Attribute;
    
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

}