<?php

namespace App\Http\Transformers;

use App\Http\Transformers\Transformer;
use URL;

class UserTransformer extends Transformer 
{
    public function transform($data) 
    {
        return [
            'id'        => $data['id'],            
            'username'  => $this->nulltoBlank($data['username']),
            'name'      => $this->nulltoBlank($data['name']),
            'email'     => $this->nulltoBlank($data['email']),
            'location'  => $this->nulltoBlank($data['location']),
            'image'     => $data['image'] ? URL::to('/').'/uploads/users/'.$data['image'] : ''
        ];
    }
    
    public function getUserInfo($data) 
    {
        return [
            'id'        => $data['id'],
            'token'     => $data['token'],
            'username'  => $this->nulltoBlank($data['username']),
            'name'      => $this->nulltoBlank($data['name']),
            'email'     => $this->nulltoBlank($data['email']),
            'location'  => $this->nulltoBlank($data['location']),
            'image'     => $data['image'] ? URL::to('/').'/uploads/users/'.$data['image'] : ''
        ];
    }
}
