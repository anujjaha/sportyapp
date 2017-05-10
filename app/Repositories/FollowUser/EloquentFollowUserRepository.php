<?php namespace App\Repositories\FollowUser;

use App\Models\FollowUser\FollowUser;
use App\Repositories\DbRepository;
use App\Exceptions\GeneralException;

class EloquentFollowUserRepository extends DbRepository implements FollowUserRepositoryContract
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
		$this->model = new FollowUser();
	}

	/**
	 * Create Record
	 *
	 * @param array $input
	 * @return mixed
	 */
	public function create($input)
	{	
		return $this->model->create($input);
	}

	public function checkRecordExist($input)
	{
		$result = $this->model->where(['user_id' => $input['user_id'], 'follower_id' => $input['follower_id']])->count();
		if($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
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

}