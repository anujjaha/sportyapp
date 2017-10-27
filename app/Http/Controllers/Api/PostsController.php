<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Transformers\PostTransformer;
use App\Http\Controllers\Controller;
use App\Repositories\Post\EloquentPostRepository;
use Auth;

class PostsController extends Controller 
{   
    /**
     * Post Transformer
     * 
     * @var Object
     */
    protected $postTransformer;

    /**
     * Repository
     * 
     * @var Object
     */
    protected $respository;

    /**
     * __construct
     * 
     * @param PostTransformer $postTransformer
     */
    public function __construct(EloquentPostRepository $respository, PostTransformer $postTransformer)
    {
        $this->respository      = $respository;
        $this->postTransformer  = $postTransformer;
    }

    public function create(Request $request)
    {
        $postData = $request->all();
        if(isset($postData['description']) && $postData['description'])
        {
            $postData['user_id']    = Auth::user()->id;
            $response               = $this->respository->create($postData);
            if($response)
            {
                $this->setSuccessMessage("Post Successfully Created");
                return $this->ApiSuccessResponse([]);
            }
            else
            {
                return $this->respondInternalError('Error in saving Post');
            }
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }
    }

    public function update(Request $request)
    {
        $postData = $request->all();
        if(isset($postData['id']) && $postData['id'])
        {
            $post = $this->respository->getById($postData['id']);

            if($post)
            {
                $response = $this->respository->update($postData['id'], $postData, $post);

                if($response)
                {
                    $this->setSuccessMessage("Post Successfully updated");
                    return $this->ApiSuccessResponse([]);
                }
                else
                {
                    return $this->respondInternalError('Error in updating Post');
                }
            }
            else
            {
                return $this->respondInternalError("Post doesn't exist.");  
            }
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }
    }

    public function getList()
    {
        $userId         = Auth::user()->id;
        $posts          = $this->respository->getAllPosts($userId);
        $responseData   = $this->postTransformer->postListWithLike($posts);

        return $this->ApiSuccessResponse($responseData);
    }

    /**
     * Get Single Item
     * 
     * @return array
     */
    public function getSingleItem(Request $request)
    {
        if($request->get('post_id'))
        {
            $userId         = Auth::user()->id;
            $post           = $this->respository->getSinglePostById($userId, $request->get('post_id'));
            $responseData   = $this->postTransformer->postListWithLike($post);

            return $this->ApiSuccessResponse($responseData);
        }

        return $this->respondInternalError('Invalid Post Id');
    }

    public function getData(Request $request)
    {
        $postData   = $request->all();
        $userId     = Auth::user()->id;
        if(isset($postData['id']) && $postData['id'])
        {
            $post           = $this->respository->getById($postData['id']);
            $post->is_liked = $this->checkPostLike($post->id, $userId);
            if($post)
            {
                $responseData   = $this->postTransformer->transform($post->toArray());
                return $this->ApiSuccessResponse($responseData);
            }
            else
            {
                return $this->respondInternalError("Post doesn't exist.");    
            }
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }
    }

    public function like(Request $request)
    {
        $postData   = $request->all();
        $userId     = Auth::user()->id; 

        if(isset($postData['post_id']) && $postData['post_id'])
        {
            $post = $this->respository->getById($postData['post_id']);
            if($post)
            {
                $postData['user_id'] = $userId;

                if(!$this->respository->checkPostLike($postData['post_id'], $userId))
                {
                    $this->respository->createPostLike($postData);

                    $this->setSuccessMessage("Post Successfully Liked");

                    return $this->ApiSuccessResponse([]);
                }
                else
                {
                    return $this->respondInternalError('Post Already Liked');   
                }
            }
            else
            {
                return $this->respondInternalError("Post doesn't exist.");
            }            
        } 
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }  
    }

    public function unLike(Request $request)
    {
        $postData   = $request->all(); 
        $userId     = Auth::user()->id;

        if(isset($postData['post_id']) && $postData['post_id'])
        {
            $post = $this->respository->getById($postData['post_id']);
            if($post)
            {
                if($this->respository->checkPostLike($postData['post_id'], $userId))
                {
                    $this->respository->destroyPostLike($postData['post_id'], $userId);

                    $this->setSuccessMessage("Post Successfully Unliked");
                    
                    return $this->ApiSuccessResponse([]);
                }
                else
                {
                    return $this->respondInternalError('Like Post First');   
                }
            }
            else
            {
                return $this->respondInternalError("Post doesn't exist.");
            }
        } 
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }  
    }

    /**
     * Create Comment
     * 
     * @param Request $request
     * @return array
     */
    public function createComment(Request $request)
    {
        if($request->get('post_id') && $request->get('comment'))
        {
            $userId = Auth::user()->id;
            $status = $this->respository->createComment($userId, $request->all());

            if($status)
            {
                $this->setSuccessMessage("Comment Added Successfully.");
                
                return $this->ApiSuccessResponse([]);
            }

            return $this->respondInternalError('Something went wrong !');
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }
    }

    /**
     * Delete Comment
     * 
     * @param  Request $request
     * @return array
     */
    public function deleteComment(Request $request)
    {
       if($request->get('post_id'))
        {
            $userId = Auth::user()->id;
            $postId = $request->get('post_id');
            $status = $this->respository->deleteComment($userId, $postId);

            if($status)
            {
                $this->setSuccessMessage("Comment Deleted Successfully.");
                
                return $this->ApiSuccessResponse([]);
            }

            return $this->respondInternalError('Something went wrong !');
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        } 
    }

    /**
     * Discover List
     * 
     * @param Request $request
     * @return json
     */
    public function discoverList(Request $request)
    {
        $userId         = Auth::user()->id;
        $posts          = $this->respository->getAllDiscoverPosts($userId);
        $responseData   = $this->postTransformer->postListWithLike($posts);

        return $this->ApiSuccessResponse($responseData);        
    }

    public function createFanChallengePost(Request $request)
    {
        if($request->get('gameId') && $request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $postData = $request->all();

            if(isset($postData['description']) && $postData['description'])
            {
                $postData['user_id']    = Auth::user()->id;
                $response               = $this->respository->createFanChallengePost($postData);
                if($response)
                {
                    $this->setSuccessMessage("Post Successfully Created");
                    return $this->ApiSuccessResponse([]);
                }
                else
                {
                    return $this->respondInternalError('Error in saving Post');
                }
            }
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }        
    }

    public function getFanChallengePost(Request $request)
    {
        if($request->get('gameId') && $request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $userId         = Auth::user()->id;
            $posts          = $this->respository->getAllFanChallengePosts($request->get('gameId'), $request->get('homeTeamId'), $request->get('awayTeamId'));
            $responseData   = $this->postTransformer->postListWithLike($posts);

            return $this->ApiSuccessResponse($responseData);        
        }

        return $this->respondInternalError('Provide Valid prameters');
    }
}
