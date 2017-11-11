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
        $videoImg = URL::to('/').'/uploads/users/video-thumbnail.png';

        if($data['is_image'] == 1 && isset($data['image']) || $data['is_wowza'] == 1)
        {
            if(isset($data['video_image']))
            {
                $videoImg = URL::to('/').'/uploads/posts/'.$data['video_image'];
            }            
        }

        return [
            'id'            => (int) $data['id'],
            'image'         => ($data['is_image'] == 1 && $data['image']) ?  URL::to('/').'/uploads/posts/'.$data['image'] : '',
            'video'         => (isset($data['is_wowza']) && $data['is_wowza'] == 1) ? $data['description'] : URL::to('/').'/uploads/posts/'.$data['image'],
            'videoImg'      => $videoImg,
            'is_image'      => $data['is_image'] ? $data['is_image'] : '',
            'description'   => (isset($data['is_wowza']) && $data['is_wowza'] == 1) ? : $this->nulltoBlank($data['description']),
            'created_at'    => date('m/d/Y H:i:s', strtotime($data['created_at'])),
            'is_liked'      => (isset($data['is_liked']) && $data['is_liked']) ? 1 : 0,
            'is_wowza'      => (isset($data['is_wowza']) && $data['is_wowza'] == 1) ? 1 : 0
        ];
    }

    public function postListWithLike($posts)
    {
        $response = [];

        $sr = 0;
        $currentUser = access()->user()->id;

        foreach($posts as $post)
        {
            $videoImg = URL::to('/').'/uploads/users/video-thumbnail.png';

            if($post->is_image == 1 && isset($post->is_image) || $post->is_wowza == 1)
            {
                if(isset($post->video_image))
                {
                    $videoImg = URL::to('/').'/uploads/posts/'.$post->video_image;
                }            
            }


            $response[$sr] = [  
                'id'                => (int) $post->id,
                'image'             => ($post->is_image == 1 && $post->image) ?  URL::to('/').'/uploads/posts/'.$post->image : '',
                'video'             => (isset($post->is_wowza) && $post->is_wowza == 1) ? $post->description : ($post->image) ? URL::to('/').'/uploads/posts/'.$post->image : '',
                'is_image'          => $post->is_image ? $post->is_image : '',
                'videoImg'          => $videoImg,
                'description'       => $this->nulltoBlank($post->description),
                 'is_wowza'         => (isset($post->is_wowza) && $post->is_wowza == 1) ? 1 :0,
                'postCategory'      => $post->post_category,
                'is_liked'          =>  0,
                'created_at'        => date('m/d/Y H:i:s', strtotime($post->created_at)),
                'can_delete'        => ($post->user->id == $currentUser) ? 1 : 0,
                'postLikeCount'     => isset($post->post_likes) ? count($post->post_likes) : 0,
                'postCommentCount'  => isset($post->post_comments) ? count($post->post_comments) : 0,
                'postLikeUser'      => [],
                'postComments'      => [],
                'postGifs'      => [],
                'postCreator'       => [
                    'userId'    => $post->user->id,
                    'name'      => $post->user->name,
                    'username'  => $post->user->username,
                    'email'     => $post->user->email,
                    'image'     => $post->user->image ? URL::to('/').'/uploads/users/'.$post->user->image : URL::to('/').'/uploads/users/default.png',
                    'location'  => $post->user->location
                ],
            ];

            if(isset($post->post_gifs))
            {
                foreach($post->post_gifs as $gif)
                {
                    if($gif->gif)
                    {
                        $response[$sr]['postGifs'][] = [
                            'gif_id'    => $gif->gif->id,
                            'gif_Image' => URL::to('/').'/uploads/gif/'.$gif->gif->gif
                        ];
                    }
                }
            }

            if(isset($post->post_comments))
            {
                foreach($post->post_comments as $postComment)
                {
                    $commentGif = false;

                    if(isset($postComment->comment_gif) && $postComment->comment_gif->gif->gif)
                    {
                        $commentGif = true;
                    }
                    
                    $response[$sr]['postComments'][] = [
                        'commentId'         => $postComment->id,
                        'commentText'       => $postComment->comment,
                        'is_image'          => $commentGif ? 1 : 0,
                        'commentImage'      => $commentGif ?  URL::to('/').'/uploads/gif/'.$postComment->comment_gif->gif->gif : '',
                        'commentCreatedAt'  => date('m-d-Y H:i:s', strtotime($postComment->created_at)),
                        'userId'    => $postComment->user->id,            
                        'username'  => $postComment->user->username,
                        'name'      => $postComment->user->name,
                        'email'     => $postComment->user->email,
                        'location'  => $postComment->user->location,
                        'can_delete'=> ($postComment->user->id == $currentUser) ? 1 : 0,
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
