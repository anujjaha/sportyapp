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
            'image'         => ($data['is_image'] == 1 && $data['image']) ?  URL::to('/').'/uploads/posts/'.$data['image'] : '',
            'video'         => ($data['is_image'] == 0 && $data['image']) ?  URL::to('/').'/uploads/posts/'.$data['image'] : '',
            'videoImg'      => ($data['is_image'] == 1 && $data['image']) || ($data['is_wowza'] == 1)  ? URL::to('/').'/uploads/users/video-thumbnail.png' : '',
            'is_image'      => $data['is_image'] ? $data['is_image'] : '',
            'description'   => $this->nulltoBlank($data['description']),
            'created_at'    => date('m/d/Y H:i:s', strtotime($data['created_at'])),
            'is_liked'      => (isset($data['is_liked']) && $data['is_liked']) ? 1 : 0
        ];
    }

    public function postListWithLike($posts)
    {
        $response = [];

        $sr = 0;
        $currentUser = access()->user()->id;

        foreach($posts as $post)
        {
            $response[$sr] = [  
                'id'                => (int) $post->id,
                'image'             => ($post->is_image == 1 && $post->image) ?  URL::to('/').'/uploads/posts/'.$post->image : '',
                'video'             => ($post->is_image == 0 && $post->image) ?  URL::to('/').'/uploads/posts/'.$post->image : '',
                'is_image'          => $post->is_image ? $post->is_image : '',
                'videoImg'          => ($post->is_image == 0 && $post->image) || $post->is_wowza == 1 ? URL::to('/').'/uploads/users/video-thumbnail.png' : '',
                'description'       => $this->nulltoBlank($post->description),
                'postCategory'      => $post->post_category,
                'is_liked'          =>  0,
                'created_at'        => date('m/d/Y H:i:s', strtotime($post->created_at)),
                'postLikeCount'     => isset($post->post_likes) ? count($post->post_likes) : 0,
                'postCommentCount'  => isset($post->post_comments) ? count($post->post_comments) : 0,
                'postLikeUser'      => [],
                'postComments'      => [],
                'postCreator'       => [
                    'userId'    => $post->user->id,
                    'name'      => $post->user->name,
                    'username'  => $post->user->username,
                    'email'     => $post->user->email,
                    'location'  => $post->user->location
                ],
            ];

            if(isset($post->post_comments))
            {
                foreach($post->post_comments as $postComment)
                {
                    $response[$sr]['postComments'][] = [
                        'commentId'         => $postComment->id,
                        'commentText'       => $postComment->comment,
                        'commentCreatedAt'  => date('m-d-Y H:i:s', strtotime($postComment->created_at)),
                        'userId'    => $postComment->user->id,            
                        'username'  => $postComment->user->username,
                        'name'      => $postComment->user->name,
                        'email'     => $postComment->user->email,
                        'location'  => $postComment->user->location,
                        'image'     => $postComment->user->image ? URL::to('/').'/uploads/users/'.$postComment->user->image : '',
                    ];
                }
            }

            if(isset($post->post_likes))
            {
                foreach($post->post_likes as $postLike)
                {
                    if($postLike->id == $currentUser)
                    {
                        $response[$sr]['is_liked']  = 1;
                    }

                    $response[$sr]['postLikeUser'][] = [
                        'id'        => $postLike->id,            
                        'username'  => $postLike->username,
                        'name'      => $postLike->name,
                        'email'     => $postLike->email,
                        'location'  => $postLike->location,
                        'image'     => $postLike->image ? URL::to('/').'/uploads/users/'.$postLike->image : '',
                    ];
                }
            }

            $sr++;
        }
        
        return $response;
    }
}
