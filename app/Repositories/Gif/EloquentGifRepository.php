<?php namespace App\Repositories\Gif;

use App\Models\Gif\Gif;
use App\Repositories\DbRepository;
use App\Exceptions\GeneralException;

class EloquentGifRepository extends DbRepository
{
	/**
	 * Gif Model
	 * 
	 * @var Object
	 */
	public $model;

	/**
	 * Table Headers
	 *
	 * @var array
	 */
	public $tableHeaders = [
		'Id',
		'Image',
		'Actions'
	];

	/**
	 * Table Columns
	 *
	 * @var array
	 */
	public $tableColumns = [
		[
			'data' 			=> 'id',
			'name' 			=> 'id',
			'searchable' 	=> true, 
			'sortable'		=> true
		],
		[
			'data' 			=> 'gif',
			'name' 			=> 'gif',
			'searchable' 	=> true, 
			'sortable'		=> true
		],
		[
			'data' 			=> 'actions',
			'name' 			=> 'actions',
			'searchable' 	=> false, 
			'sortable'		=> false
		]
	];

	/**
	 * Table Fields
	 * 
	 * @var array
	 */
	public $tableFields = [
	];

	/**
	 * Construct
	 *
	 */
	public function __construct()
	{
		$this->model 		= new Gif;
	}

	/**
	 * Create GIf
	 *
	 * @param array $input
	 * @return mixed
	 */
	public function create($input)
	{
		$input = $this->prepareInputData($input, true);
		
		return $this->model->create($input);
	}	

	/**
	 * Update Video
	 *
	 * @param int $id
	 * @param array $input
	 * @return bool|int|mixed
	 */
	public function update($id, $input)
	{
		$model = $this->findOrThrowException($id);
		$input = $this->prepareInputData($input);		
		
		return $model->update($input);
	}

	/**
	 * Destroy Video
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

		throw new GeneralException(trans('exceptions.backend.access.roles.delete_error'));
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
     * Get Table Fields
     * 
     * @return array
     */
    public function getTableFields()
    {
    	return [
			$this->model->getTable().'.id as id',
			$this->model->getTable().'.gif'
		];
    }

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
    	return  $this->model->select($this->getTableFields())->get();
        
    }

    /**
     * Prepare Input Data
     * 
     * @param array $input
     * @param bool $isCreate
     * @return array
     */
    public function prepareInputData($input = array(), $isCreate = false)
    {
    	if($isCreate)
    	{
    		$input = array_merge($input, ['user_id' => access()->user()->id]);
    	}

    	if(isset($input['start_date']) && isset($input['end_date']))
    	{
    		$input['start_date'] 	= date('Y-m-d', strtotime($input['start_date']));
    		$input['end_date'] 		= date('Y-m-d', strtotime($input['end_date']));

    		return $input;
    	}

    	return $input;
    }

    /**
     * Get Table Headers
     *
     * @return string
     */
    public function getTableHeaders()
    {
    	return json_encode($this->tableHeaders);
    }

    /**
     * Get Table Columns
     *
     * @return string
     */
    public function getTableColumns()
    {
    	return json_encode($this->tableColumns);
    }
}