<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Transformers\FansTransformer;
use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\Backend\Access\User\UserRepository;

class APIFansController extends BaseApiController 
{   
    /**
     * Event Transformer
     * 
     * @var Object
     */
    protected $fanTransformer;

    /**
     * __construct
     * 
     * @param EventTransformer $eventTransformer
     */
    public function __construct()
    {
        parent::__construct();

        $this->respository      = new UserRepository;
        $this->eventTransformer = new FansTransformer;
    }

    /**
     * List of All Events
     * 
     * @param Request $request
     * @return json
     */
    public function index(Request $request) 
    {
        $userInfo   = $this->getApiUserInfo();
        $events     = $this->respository->getAll()->toArray();
        $eventsData = $this->eventTransformer->transformCollection($events);

        $responseData = array_merge($userInfo, ['events' => $eventsData]);

        // if no errors are encountered we can return a JWT
        return response()->json($responseData);
    }
}