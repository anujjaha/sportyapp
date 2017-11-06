<?php namespace App\Repositories\Post;

use App\Models\Post\Post;
use App\Models\Post\PostComment;
use App\Models\Post\PostLike;
use App\Repositories\DbRepository;
use App\Exceptions\GeneralException;
use App\Models\PostGif\PostGif;

class EloquentPostRepository extends DbRepository implements PostRepositoryContract
{
	/**
	 * Event Model
	 * 
	 * @var Object
	 */
	public $model;

	/**
	 * Construct
	 *
	 */
	public function __construct()
	{
		$this->model 	= new Post();
		$this->postLike = new PostLike();
	}

	/**
	 * Create Record
	 *
	 * @param array $input
	 * @return mixed
	 */
	public function create($postData)
	{
		$destinationFolder  = public_path().'/uploads/posts';

		$postData['is_image'] = isset($postData['is_image']) ? $postData['is_image'] : 1;


        if (isset($postData['image']) && $postData['image']) { 
            $extension = $postData['image']->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            if($postData['image']->move($destinationFolder, $fileName))
            {
                $postData['image'] = $fileName;
            }                       
        }
		return $this->model->create($postData);
	}

	/**
	 * Create Record
	 *
	 * @param array $input
	 * @return mixed
	 */
	public function update($id, $postData, $model = null)
	{
		if($model)
		{
			$model = $this->findOrThrowException($id);	
		}		

		$destinationFolder  = public_path().'/uploads/posts';

        if (isset($postData['image']) && $postData['image']) 
        { 
            $extension 	= $postData['image']->getClientOriginalExtension();
            $fileName 	= rand(11111,99999).'.'.$extension;
            if($postData['image']->move($destinationFolder, $fileName))
            {
                $postData['image'] = $fileName;
            }                       
        }

		return $model->update($postData);
	}

	/**
	 * Destroy Record
	 *
	 * @param int $id
	 * @return mixed
	 * @throws GeneralException
	 */
	public function destroy($id)
	{
		$model = $this->findOrThrowException($id);

		if($model)
		{
			return $model->delete();
		}

		throw new GeneralException("Error in Deleting Record");
	}

	/**
	 * Find and Destroy Record
	 *
	 * @param int $id
	 * @return mixed
	 * @throws GeneralException
	 */
	public function findAndDestroy($input)
	{
		$result = $this->model->where(['user_id' => $input['user_id'], 'follower_id' => $input['follower_id']])->delete();

		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
     * Get All
     *
     * @param object $videos [all videos]
     * @param boolean $hashed
     * @return mixed
     */
    public function getAll($orderBy = 'id', $sort = 'asc')
    {
        return $this->model->all();
    }

	/**
     * Get by Id
     *
     * @param object $videos [all videos]
     * @param boolean $hashed
     * @return mixed
     */
    public function getById($id = null)
    {
    	if($id)
    	{
    		return $this->model->find($id);
    	}
        
        return false;
    } 

    /**
     * Get AllPosts
     * 
     * @return object
     */
   	public function getAllPosts($userId = null)
   	{
   		return $this->model->with('post_likes')->where(['home_team_id' => NULL, 'away_team_id' => NULL])->orderBy('id', 'desc')->get();
   	}

   	/**
     * Get Single Post By Id
     * 
     * @return object
     */
   	public function getSinglePostById($userId = null, $postId = null)
   	{
   		return $this->model->with('post_likes')->where('id', $postId)->orderBy('id', 'desc')->get();
   	}

    public function getPostListByFollower($userId, $page = 1)
     {
     	$posts = $this->model->leftJoin('follow_user', 'follow_user.user_id', '=', 'posts.user_id')
     			->where('follow_user.follower_id', '=', $userId)
     			->orWhere('posts.user_id', '=', $userId)
     			->select('posts.*')
                ->orderBy('posts.created_at', 'DESC');

        $posts = $posts->get();
        foreach($posts as $key => $value)
        {
        	$posts[$key]->is_liked = $this->checkPostLike($value->id, $userId);
        }
        return $posts;	
     } 

    public function checkPostLike($postId, $userId)
    {
    	$check = $this->postLike
				 ->where(['post_id' => $postId, 'user_id' => $userId])    			
    			 ->count(); 
    	if($check > 0)
    	{
    		return true;
    	}	
    	return false;
    }

    public function createPostLike($postData)
    {
    	return $this->postLike->create($postData);
    }

    /**
	 * Destroy Record
	 *
	 * @param int $id
	 * @return mixed
	 * @throws GeneralException
	 */
	public function destroyPostLike($postId, $userId)
	{
		$model = $this->postLike->where(['post_id' => $postId, 'user_id' => $userId]);

		if($model)
		{
			return $model->delete();
		}
		return false;
	}

	/**
	 * Create Comment
	 * 
	 * @param int $userId
	 * @param array  $input
	 * @return bool
	 */
	public function createComment($userId = null, $input = array())
	{
		if($userId && count($input))
		{
			$commentData = [
				'user_id' 	=> $userId,
				'post_id'	=> $input['post_id'],
				'comment'	=> $input['comment']
			];

			return PostComment::create($commentData);
		}

		return false;
	}

	/**
	 * Delete Comment
	 * 
	 * @param int $userId
	 * @param int $postId
	 * @return bool
	 */
	public function deleteComment($userId = null, $postId = null)
	{
		if($userId && $postId)
		{
			$postComment = new PostComment;

			return $postComment->where(['user_id' => $userId, 'post_id' => $postId])->delete();
		}

		return false;
	}

	public function getAllDiscoverPosts($userId = null)
	{
		$posts = $this->model->with('post_likes')->orderBy('id', 'desc')->get()->filter(function($item)
			{
				return $item->postCount = count($item->post_likes);
			});		

		return $posts->sortByDesc('postCount');
	}

	public function createFanChallengePost($postData)
	{
		$destinationFolder  = public_path().'/uploads/posts';

		$postData['is_image'] = isset($postData['is_image']) ? $postData['is_image'] : 1;


        if (isset($postData['image']) && $postData['image']) { 
            $extension = $postData['image']->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            if($postData['image']->move($destinationFolder, $fileName))
            {
                $postData['image'] = $fileName;
            }                       
        }

        $postData['game_id'] = $postData['gameId'];
        $postData['home_team_id'] = $postData['homeTeamId'];
        $postData['away_team_id'] = $postData['awayTeamId'];

		return $this->model->create($postData);		
	}

	public function getAllFanChallengePosts($gameId = null , $homeTeamId = null, $awayTeamId = null)
	{
		if($gameId && $homeTeamId && $awayTeamId)
		{
			return $this->model->with('post_likes')->where([
				'game_id' 		=> $gameId,
				'home_team_id' 	=> $homeTeamId,
				'away_team_id' 	=> $awayTeamId
			])->orderBy('id', 'desc')->get();			
		}

		return false;
	}

	public function addGif($userId, $postId, $gifId)
	{
		if($userId && $postId && $gifId)
		{
			$deleteStatus = $this->removeGif($userId, $postId, $gifId);

			return postGif::create([
				'user_id' => $userId,
				'post_id' => $postId,
				'gif_id'  => $gifId
			]);
		}

		return false;
	}

	public function removeGif($userId, $postId, $gifId)
	{
		if($userId && $postId && $gifId)
		{
			return PostGif::where(['user_id' => $userId, 'post_id' => $postId, 'gif_id' => $gifId])->delete();
		}

		return false;
	}
}