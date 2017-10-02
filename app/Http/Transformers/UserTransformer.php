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
            'image'     => $data['image'] ? URL::to('/').'/uploads/users/'.$data['image'] : '',
            'is_follow' => (isset($data['is_follow']) && $data['is_follow']) ? 1 : 0
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

    /**
     * FanTransform
     * 
     * @param object $items
     * @return array
     */
    public function fanTransform($items)
    {
        $response = [];
        foreach($items as $item)
        {
            $item = (object)$item;

            $response[] = [
                'id'        => $item->id,            
                'username'  => $this->nulltoBlank($item->username),
                'name'      => $this->nulltoBlank($item->name),
                'email'     => $this->nulltoBlank($item->email),
                'location'  => $this->nulltoBlank($item->location),
                'image'     => $item->image ? URL::to('/').'/uploads/users/'.$item->image : '',
                'is_follow' => 1,
                'follow_time' => "10 min ago"
            ];
        }

        return $response;
    }
}
