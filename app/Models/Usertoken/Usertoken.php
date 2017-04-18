<?php

namespace App\Models\Usertoken;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usertoken\Traits\Relationship\Relationship;

class Usertoken extends Model {

    /**
     * use Replationship
     */
    use Relationship;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;    

    /**
     * [__construct description]
     */
    public function __construct() {
        $this->table = 'user_tokens';
    }

}
