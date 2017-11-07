<?php namespace App\Http\Controllers\Api;

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
        if($request->get('post_id'))
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
       if($request->get('comment_id'))
        {
            $userId     = Auth::user()->id;
            $commentId  = $request->get('comment_id');
            $status     = $this->respository->deleteComment($userId, $commentId);

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

    public function delete(Request $request)
    {
        if($request->get('post_id'))
        {
            $userId = Auth::user()->id;
            $postId = $request->get('post_id');
            $status = $this->respository->deletePost($userId, $postId);

            if($status)
            {
                $this->setSuccessMessage("Post Deleted Successfully.");
                
                return $this->ApiSuccessResponse([
                    'postDeleted'   => 1,
                    'message'       => 'Post Deleted Successfully.'
                    ]);
            }

            $this->setSuccessMessage("Not Authorized to delete the Post ! Try Again !");
                
            return $this->ApiSuccessResponse([
                'postDeleted'   => 0,
                'message'       => 'Unable to Delete Post.'
                ]);
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
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }
        
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
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }

        if($request->get('gameId') && $request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $userId         = Auth::user()->id;
            $posts          = $this->respository->getAllFanChallengePosts($request->get('gameId'), $request->get('homeTeamId'), $request->get('awayTeamId'));
            $responseData   = $this->postTransformer->postListWithLike($posts);

            return $this->ApiSuccessResponse($responseData);        
        }

        return $this->respondInternalError('Provide Valid prameters');
    }

    public function addGif(Request $request)
    {
        $user = Auth::user();

        if($request->get('post_id') && $request->get('gif_id'))
        {
            $postGif = $this->respository->addGif($user->id, $request->get('post_id'), $request->get('gif_id'));

            if($postGif)
            {
                $this->setSuccessMessage("Added GIF Successfully");
                return $this->ApiSuccessResponse([]);
            }
               
        }
        
        return $this->respondInternalError('Error in Adding Gif Post');
    }

    public function removeGif(Request $request)
    {
        $user = Auth::user();

        if($request->get('post_id') && $request->get('gif_id'))
        {
            $postGif = $this->respository->removeGif($user->id, $request->get('post_id'), $request->get('gif_id'));

            if($postGif)
            {
                $this->setSuccessMessage("Added GIF Successfully");
                return $this->ApiSuccessResponse([]);
            }
               
        }
    }

    public function createGameTimeLine(Request $request)
    {
        $postData = $request->all();

        if(isset($postData['description']) && $postData['description'])
        {
            $postData['user_id']    = Auth::user()->id;
            $response               = $this->respository->gamePostcreate($postData);
            if($response)
            {
                $this->setSuccessMessage("Game Post Successfully Created");
                return $this->ApiSuccessResponse([]);
            }
            else
            {
                return $this->respondInternalError('Error in saving Game Post');
            }
        }
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }        
    }

    public function getGameTimeLine(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }

        if($request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $posts  = $this->respository->getGamePosts($request->get('gameId'), $request->get('homeTeamId'), $request->get('awayTeamId'));
            $responseData   = $this->postTransformer->postListWithLike($posts);

            return $this->ApiSuccessResponse($responseData);        
        }

        return $this->respondInternalError('Invalid Inputs');
    }

    public function checkGameTimeLine(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }

        if($request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $posts  = $this->respository->checkGamePosts($request->get('gameId'), $request->get('homeTeamId'), $request->get('awayTeamId'));

            if($posts && count($posts) > 0)
            {
                $this->setSuccessMessage("Game Post Found Successfully.");
                
                return $this->ApiSuccessResponse([
                    'postFound'   => 1,
                    'message'       => 'Game Post Found Successfully.'
                    ]);
            }

             $this->setSuccessMessage("Game Post Not Found.");
                
            return $this->ApiSuccessResponse([
                'postFound'   => 0,
                'message'       => 'Game Post not Found.'
                ]);
        }

        return $this->respondInternalError('Invalid Inputs');
    }
}
