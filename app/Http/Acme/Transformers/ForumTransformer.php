<?php
/*
 * Forum Transformer File for APIs
 * Created By: Niraj Jani
 * Created At: 12/28/2016
 */
namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class ForumTransformer extends Transformer {

    public function transform($data) {
        
        return [
            'ForumId' => $data['id'],
            'ForumTitle' => $this->nulltoBlank($data['subject']),
            'ForumDescription' =>  $this->nulltoBlank($data['content']),
            'ForumImage' => !empty($data['forum_image'])?$this->getForumImage($data['forum_image'][0]['image']):"",
            'CommentCount' => $data['comment_count'],
            'Manufacturer' => array(
                'ManufacturerId' => $data['forum_user']['id'],
                'ManufacturerName' => $this->nulltoBlank($data['forum_user']['company_name']),
                'ProfilePhoto' => $data['forum_user']['profile_photo']?$this->getUserImage($data['forum_user']['profile_photo']):""
            )
        ];
    }
    /*
     * Get Forum Listing array mapping
     * Created By: Shakil Mansuri
     * Created At: 01/12/2017
     * @param array $items
     * @return array
     */
    public function getForumListCollection(array $items) {
        return array_map([$this, 'getForumList'], $items);
    }

    /*
     * Get Forum Listing
     * Created By: Shakil Mansuri
     * Created At: 01/12/2017
     * @param array $data
     * @return array
     */
    public function getForumList($data)
    {
         return [
            'ForumId' => $data['id'],
            'ForumTitle' => $this->nulltoBlank($data['subject']),
            'ForumDescription' =>$data['content'],
            'ForumImage' => !empty($data['forum_image'])?$this->getForumImage($data['forum_image']):"",
            'CommentCount' => $data['comment_count'],
            'Manufacturer' => array(
                'ManufacturerId' => $data['userId'],
                'ManufacturerName' => $this->nulltoBlank($data['company_name']),
                'ProfilePhoto' => $data['profile_photo']?$this->getUserImage($data['profile_photo']):""
            )
        ];
    }
    
    /*
     * Get Forum Image Path
     * Created By: Niraj Jani
     * Created At: 11/28/2016
     * @param string $profilePhoto
     * @return string
     */
    
    public function getForumImage($profilePhoto){        
        return $profilePhoto?url('/').'/images/'.config('backend.forum_image_folder').'/'.$profilePhoto:"";
    }
    
    /*
     * Get User Image Path
     * Created By: Niraj Jani
     * Created At: 11/28/2016
     * @param string $profilePhoto
     * @return string
     */
    
    public function getUserImage($profilePhoto){        
        return $profilePhoto?url('/').'/images/'.config('access.users.image_folder').'/'.$profilePhoto:"";
    }
    
    /*
     * Get Comment Array
     * Created By: Niraj Jani
     * Created At: 11/28/2016
     * 
     * @param array $data
     * @return array
     */
    public function transformComments($data){        
        return[
            'CommentCount' => $data['comment_count']
        ];
    }
    
    /*
     * Get Comment collection Array
     * Created By: Niraj Jani
     * Created At: 11/28/2016
     * 
     * @param array $data
     * @return array
     */
    public function transformCommentCollection(array $items) {
        return array_map([$this, 'singeCommentTransform'], $items);
    }
    
    /*
     * Get Single Comment Data
     * Created By: Niraj Jani
     * Created At: 11/28/2016
     * 
     * @param array $data
     * @return array
     */
    public function singeCommentTransform($data){
        return[
            'ForumId' => $data['forum_id'],
            'UserId' => $data['forum_comment_user']['id'],
            'Name' => $this->nulltoBlank($data['forum_comment_user']['name']),
            'ProfilePhoto' => $data['forum_comment_user']['profile_photo']?$this->getUserImage($data['forum_comment_user']['profile_photo']):"",            
            'Comment' => $this->nulltoBlank($data['comment']),
            'CommentTime' => strtotime($data['created_at']),
            'CommentImages' => $this->getCommentImages($data['forum_comment_image'],$data['id'])
        ];
    }
    
    public function getCommentImages($images,$CommentId){
        $commentArray = array();
        foreach($images as $iK => $iV){
            $commentArray[$iK] = url('/').'/images/forum-comment/'.$CommentId.'/'.$iV['image'];
        }
        return $commentArray;
    }

}
