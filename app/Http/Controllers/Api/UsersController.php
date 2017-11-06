<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Access\User\User;
use Response;
use Carbon;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Repositories\Backend\UserNotification\UserNotificationRepositoryContract;
use App\Http\Transformers\UserTransformer;
use App\Http\Utilities\FileUploads;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Auth;
use App\Repositories\FollowUser\EloquentFollowUserRepository;


class UsersController extends Controller 
{
    protected $userTransformer;
    /**
     * __construct
     * @param UserTransformer                    $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer  = $userTransformer;
        $this->users            = new UserRepository();
        $this->followUser       = new EloquentFollowUserRepository();
    }

    /**
     * Login request
     * 
     * @param Request $request
     * @return type
     */
    public function login(Request $request) 
    {
        $credentials = $request->only('username', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->respondInternalError('invalid_credentials');
            }
        } catch (JWTException $e) {
            return $this->respondInternalError('could_not_create_token');
        }
        
        $user = Auth::user()->toArray();

        $userData = array_merge($user, ['token' => $token]);
        $responseData = $this->userTransformer->getUserInfo($userData);

        // if no errors are encountered we can return a JWT
        return $this->ApiSuccessResponse($responseData);
    }

    /**
     * Logout request
     * @param  Request $request
     * @return json
     */
    public function logout(Request $request) 
    {
        /*$userId = $request->header('UserId');
        $userToken = $request->header('UserToken');
        $response = $this->users->deleteUserToken($userId, $userToken);
        if ($response) {
            return $this->ApiSuccessResponse(array());
        } else {
            return $this->respondInternalError('Error in Logout');
        }*/
    }
    
    public function register(Request $request) {
        $postData = $request->all();
        if (isset($postData['username']) && $postData['username'] &&
                isset($postData['password']) && $postData['password'] &&
                isset($postData['name']) && $postData['name']
        ) {
            if (isset($postData['email']) && !$this->users->checkEmailAlreadyExist($postData['email'])) {
                return $this->respondInternalError('User\'s Email Already Exist');
            }
            if (!$this->users->checkUserNameAlreadyExist($postData['username'])) {
                return $this->respondInternalError('Username Already Exist');
            }
            $user = $this->users->createAppUser($postData);
            //check user is created 
            if ($user) {
                $this->setStatusCode(200);
                
                $credentials = $request->only('username', 'password');
                try {
                    // verify the credentials and create a token for the user
                    if (! $token = JWTAuth::attempt($credentials)) {
                        return response()->json(['error' => 'invalid_credentials'], 401);
                    }
                } catch (JWTException $e) {
                    // something went wrong
                    return response()->json(['error' => 'could_not_create_token'], 500);
                }
                
                $user = Auth::user()->toArray();

                $userData = array_merge($user, ['token' => $token]);
                
                $responseData = $this->userTransformer->getUserInfo($userData);
                return $this->ApiSuccessResponse($responseData);
            } else {
                return $this->respondInternalError('Invalid Arguments');
            }
        } else {
            return $this->respondInternalError('Invalid Arguments');
        }
    }
    
    public function facebookLogin(Request $request) {
        $postData = $request->all();
        
        if (isset($postData['facebook_data']) && $postData['facebook_data']) 
        {
            $fbData = json_decode($postData['facebook_data'], true);
            
            $user = $this->users->createFbUser($fbData);
            //check user is created 
            if ($user) {
                $this->setStatusCode(200);
                try {
                    // verify the credentials and create a token for the user
                    if (! $token = JWTAuth::fromUser($user)) {
                        return response()->json(['error' => 'invalid_credentials'], 401);
                    }
                } catch (JWTException $e) {
                    // something went wrong
                    return response()->json(['error' => 'could_not_create_token'], 500);
                }
                $user = $user->toArray();

                $userData = array_merge($user, ['token' => $token]);
                
                $responseData = $this->userTransformer->getUserInfo($userData);
                return $this->ApiSuccessResponse($responseData);
            } else {
                return $this->respondInternalError('Invalid Arguments');
            }
        } else {
            return $this->respondInternalError('Invalid Arguments');
        }
    }
    
    public function getData()
    {
        $userData = Auth::user();
        
        $responseData = $this->userTransformer->transform($userData);
        return $this->ApiSuccessResponse($responseData);
    }

    /**
     * Get Fan Data
     * 
     * @param Request $request
     * @return json
     */
    public function getFanData(Request $request)
    {
        $user = Auth::user();
        
        $celebraty      = $this->users->getCelebratyFans($user->id);
        $normalFans     = $this->users->getNormalFans($user->id);
        $celebratyData  = $this->userTransformer->fanTransform($celebraty->toArray());
        $normalData     = $this->userTransformer->fanTransform($normalFans->toArray());

        $responseData = [
            'celebrity' => $celebratyData,
            'normal'    => $normalData
        ];

        
        return $this->ApiSuccessResponse($responseData);
    }

    /**
     * Get News Data
     * 
     * @param Request $user 
     * @return json
     */
    public function getNewsData(Request $request)
    {
        $user = Auth::user();
        
        $newsData      = $this->users->getNews();
        
        return $this->ApiSuccessResponse($newsData);
    }
    
    public function update(Request $request)
    {
        $postData = $request->all();
        if($this->users->updateAppUser(Auth::user(), $postData))
        {
            return $this->ApiSuccessResponse([]);
        }
        else
        {
            return $this->respondInternalError('Error in Updating data');
        }        
    }
    
    public function getList(Request $request)
    {
        $postData = $request->all();
        $users = $this->users->getAppUserList($postData, Auth::user()->id);
        $responseData = $this->userTransformer->transformCollection($users->toArray());
        return $this->ApiSuccessResponse($responseData);
    }

    public function checkUserName(Request $request)
    {
        $postData = $request->all();   
        if (!$this->users->checkUserNameAlreadyExist($postData['username'])) {
            return $this->respondInternalError('Username Already Exist');
        } 
        else
        {
            return $this->ApiSuccessResponse([]);
        }
    }

    public function follow(Request $request)
    {
        $postData = $request->all(); 

        if(isset($postData['user_id']) && $postData['user_id'])
        {
            $postData['follower_id'] = Auth::user()->id;

            if(!$this->followUser->checkRecordExist($postData))
            {
                $this->followUser->create($postData);

                $this->setSuccessMessage("User Successfully Follwed");

                return $this->ApiSuccessResponse([]);
            }
            else
            {
                return $this->respondInternalError('User Already Followed');   
            }
        } 
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }  
    }

    public function unFollow(Request $request)
    {
        $postData = $request->all(); 

        if(isset($postData['user_id']) && $postData['user_id'])
        {
            $postData['follower_id'] = Auth::user()->id;

            if($this->followUser->checkRecordExist($postData))
            {
                $this->followUser->findAndDestroy($postData);

                $this->setSuccessMessage("User Successfully Unfollwed");
                
                return $this->ApiSuccessResponse([]);
            }
            else
            {
                return $this->respondInternalError('Follow User First');   
            }
        } 
        else
        {
            return $this->respondInternalError('Provide Valid prameters');
        }  
    }

    public function getMyTeams(Request $request)
    {
        $user           = Auth::user();
        $team           = $this->users->getMyTeam($user);
        $responseData   = $this->userTransformer->myTeamTransform($team);

        return $this->ApiSuccessResponse($responseData);
    }

    /**
     * Get Fan Data
     * 
     * @param Request $request
     * @return json
     */
    public function getAllTeams(Request $request)
    {
        $team           = $this->users->getAllTeam();
        $user           = Auth::user();
        $responseData   = $this->userTransformer->teamTransform($user, $team);

        return $this->ApiSuccessResponse($responseData);
    }

    public function followTeam(Request $request)
    {
        if($request->get('team_id'))
        {
            $user = Auth::user();

            $status = $this->users->followTeam($user->id, $request->get('team_id'));

            if($status)
            {
                $responseData = [
                    'success' => 'Follow Team Successfully !'
                ];

                return $this->ApiSuccessResponse($responseData, 'Follow Team Successfully !');
            }
        }

        $error = [
            'message' => "Unable to Follow Team"
        ];

        return $this->setStatusCode(404)->ApiSuccessResponse($error, 'Something went wrong !');
    }

    public function unFollowTeam(Request $request)
    {
        if($request->get('team_id'))
        {
            $user = Auth::user();

            $status = $this->users->unFollowTeam($user->id, $request->get('team_id'));

            if($status)
            {
                $responseData = [
                    'success' => 'Un-Follow Team Successfully !'
                ];

                return $this->ApiSuccessResponse($responseData, 'Un-Follow Team Successfully !');
            }
        }

        $error = [
            'message' => "Unable to Remove Team from Follow List"
        ];

        return $this->setStatusCode(404)->ApiSuccessResponse($error, 'Something went wrong !');
    }

    public function getMyProfile(Request $request)
    {
        $user           = Auth::user();
        $team           = $this->users->getMyFollowTeam($user);
        $followTeam     = $this->users->getMyFollowTeams($user);
        $following      = $this->followUser->getMyFollowing($user);
        $followers      = $this->followUser->getMyFollowers($user);
        $posts          = $this->users->getUserPosts($user);
        
        $responseData   = [
            'total_shots'       => count($posts),
            'follow_teams_count'=> count($team),
            'follow_teams'      => $followTeam ? $followTeam : [],
            'following_count'   => count($following),
            'followers_count'   => count($followers)
        ];

        return $this->ApiSuccessResponse($responseData);        
    }

    public function getFollowers(Request $request)
    {
        $user           = Auth::user();
        $followers      = $this->followUser->getMyFollowers($user);
        $responseData   = $this->userTransformer->getMyFollowers($followers);

        return $this->ApiSuccessResponse($responseData);        
    }

    public function getGifs(Request $request)
    {
        $gifs = $this->users->getAllGif();
        $responseData   = $this->userTransformer->getFo($gifs);
        
        return $this->ApiSuccessResponse($responseData);        
    }

    public function getUserProfile(Request $request)
    {
        if($request->get('user_id'))
        {
            $userData = $this->users->getUserProfile($request->get('user_id'));
            $responseData = $this->userTransformer->transform($userData);

            return $this->ApiSuccessResponse($responseData);
        }

        return $this->respondInternalError('Provide Valid Params!');     
    }

    public function getUserProfileData(Request $request)
    {
        if($request->get('user_id'))
        {
            $user           = $this->users->getUserById($request->get('user_id'));
            $team           = $this->users->getMyFollowTeam($user);
            $followTeam     = $this->users->getMyFollowTeams($user);
            $following      = $this->followUser->getMyFollowing($user);
            $followers      = $this->followUser->getMyFollowers($user);
            $posts          = $this->users->getUserPosts($user);
            
            $responseData   = [
                'total_shots'       => count($posts),
                'follow_teams_count'=> count($team),
                'follow_teams'      => $followTeam ? $followTeam : [],
                'following_count'   => count($following),
                'followers_count'   => count($followers)
            ];

            return $this->ApiSuccessResponse($responseData);        
        }

        return $this->respondInternalError('Provide Valid Params!');     
    }

    public function addUserLocation(Request $request)
    {
        if($request->get('lat') && $request->get('long'))
        {
            $user = Auth::user();            

            $status = $this->users->updateLocation($user->id, $request->get('lat'), $request->get('long'));
            
            if($status)
            {
                $responseData = [
                    'success' => 'Location Updated Successfully !'
                ];

                return $this->ApiSuccessResponse($responseData, 'Location Updated Successfully !');
            }
        }

        $error = [
            'message' => "Unable to Remove Team from Follow List"
        ];

        return $this->setStatusCode(404)->ApiSuccessResponse($error, 'Something went wrong !');            
    }
}

