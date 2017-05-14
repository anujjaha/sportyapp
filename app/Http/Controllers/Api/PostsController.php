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
        if(isset($postData['description']) && $postData['description'] && isset($postData['image']) && $postData['image'])
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
        $posts          = $this->respository->getPostListByFollower($userId);
        $responseData   = $this->postTransformer->transformCollection($posts->toArray());
        return $this->ApiSuccessResponse($responseData);
    }

    public function getData(Request $request)
    {
        $postData = $request->all();
        if(isset($postData['id']) && $postData['id'])
        {
            $post = $this->respository->getById($postData['id']);
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
}
