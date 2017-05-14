<?php 
namespace App\Http\Transformers;

use App\Http\Transformers\Transformer;
use URL;

class PostTransformer extends Transformer 
{
    /**
     * Transform
     * 
     * @param array $data
     * @return array
     */
    public function transform($data) 
    {
        return [
            'id'            => (int) $data['id'],
            'image'         => $data['image'] ? URL::to('/').'/uploads/posts/'.$data['image'] : '',
            'description'   => $this->nulltoBlank($data['description']),
            'created_at'    => date('m/d/Y H:i:s', strtotime($data['created_at']))
        ];
    }
}
