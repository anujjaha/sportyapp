<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use App\Http\Transformers\FansTransformer;
use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\Backend\Access\User\UserRepository;
use Auth;

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
        //$this->eventTransformer = new FansTransformer;
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

    public function teamRatio(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }

        if($request->get('gameId'))
        {
            $response = $this->respository->getTeamRatio($request->get('gameId'));

            return $this->ApiSuccessResponse($response);
        }
        
        return $this->respondInternalError('Provide Valid Game Id');        
    }

    public function addTeamRatio(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }

        if($request->get('gameId') && $request->get('followTeam'))
        {
            $userId = Auth::user()->id;
            $status = $this->respository->addTeamRatio($userId, $request->all());

            if($status)
            {
                $response = $this->respository->getTeamRatio($request->get('gameId'));

                return $this->ApiSuccessResponse($response);
            }
        }
        
        return $this->respondInternalError('Provide Valid Game Id');        
    }

    public function createFanChallenge(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }
        if($request->get('gameId') && $request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $userId = Auth::user()->id;
            $status = $this->respository->createFanChallenge($userId, $request->all());

            if($status)
            {
                $response = [
                    'message' => "Fan Challenge created Successfully!"
                ];

                return $this->ApiSuccessResponse($response);
            }
        }
        
        return $this->respondInternalError('Provide Valid Params or Challenge Already Exists !');     
    }

    public function checkFanChallenge(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' =>0]);
        }

        if($request->get('gameId') && $request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $userId = Auth::user()->id;
            $status = $this->respository->checkFanChallenge($userId, $request->all());

            if($status)
            {
                $response = [
                    'fanFound'  => 1,
                    'message'   => "Fan Challenge Found !"
                ];

                return $this->ApiSuccessResponse($response);
            }
            else
            {
                $response = [
                    'fanFound'  => 0,
                    'message'   => "No Fan Challenge Found !"
                ];

                return $this->ApiSuccessResponse($response);
            }
        }
        
        return $this->respondInternalError('No Fan Challenge Found!');      
    }

    public function checkFanMeter(Request $request)
    {
        if(! $request->get('gameId'))
        {
            $request->request->add(['gameId' => 0]);
        }

        if($request->get('gameId') && $request->get('homeTeamId') && $request->get('awayTeamId'))
        {
            $userId = Auth::user()->id;
            $status = $this->respository->checkFanMeter($userId, $request->get('gameId'), $request->get('homeTeamId'), $request->get('awayTeamId'));


            if($status == false)
            {
                $response = [
                    'fanMeterFound' => 0,
                    'message'       => "Fan Not Meter Found!"
                ];                
            return $this->ApiSuccessResponse($response);            
            }

        }

        $response = [
                'fanMeterFound' => 1,
                'message'       => "Fan Meter Found!"
            ];    

        return $this->ApiSuccessResponse($response);            

    }
}
