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
            'image'     => $data['image'] ? URL::to('/').'/uploads/users/'.$data['image'] : URL::to('/').'/uploads/users/default.png',
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
            'image'     => $data['image'] ? URL::to('/').'/uploads/users/'.$data['image'] : URL::to('/').'/uploads/users/default.png'
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
                'image'     => $item->image ? URL::to('/').'/uploads/users/'.$item->image : URL::to('/').'/uploads/users/default.png',
                'is_follow' => 1,
                'team_name' => 'Rams',
                'follow_time' => "10 min ago"
            ];
        }

        return $response;
    }

    public function teamTransform($teams = null)
    {
        $response = [];
        
        if($teams)
        {
            foreach($teams as $item) 
            {
                $response[] = [
                    'id'        => (int) $item->id,     
                    'team_id'   => (int) $item->team_id,     
                    'name'      => $item->name,
                    'location'  => $item->location,
                    'image'     => $item->image ? URL::to('/').'/uploads/team/'.$item->image : URL::to('/').'/uploads/team/default.png',
                ];
            }
        }

        return $response;
    }

    public function myTeamTransform($teams = null)
    {
        $response = [];
        
        if($teams)
        {
            foreach($teams as $item) 
            {
                $response[] = [
                    'id'        => (int) $item->id,     
                    'team_id'   => (int) $item->team_id,     
                    'name'      => $item->name,
                    'location'  => $item->location,
                    'is_follow' => 1,
                    'image'     => $item->image ? URL::to('/').'/uploads/team/'.$item->image : URL::to('/').'/uploads/team/default.png',
                ];
            }
        }

        return $response;
    }
}
