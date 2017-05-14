<?php namespace App\Repositories\Post;

use App\Models\Post\Post;
use App\Repositories\DbRepository;
use App\Exceptions\GeneralException;

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
		$this->model = new Post();
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

    public function getPostListByFollower($userId, $page = 1)
     {
     	$list = $this->model->leftJoin('follow_user', 'follow_user.user_id', '=', 'posts.user_id')
     			->where('follow_user.follower_id', '=', $userId)
     			->select('posts.*')
                ->orderBy('posts.created_at', 'DESC');

     	if(isset($param['search']) && $param['search'])
        {
            $users = $users->where('users.name', 'LIKE', $param["search"].'%');
        }
        return $list->get();	
     } 

}