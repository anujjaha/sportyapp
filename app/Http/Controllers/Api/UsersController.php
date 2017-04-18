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
use App\Http\Acme\Transformers\UserTransformer;

class UsersController extends Controller {

    /**
     * __construct
     * @param UserRepository                     $users          
     * @param UserTransformer                    $userTransformer
     * @param UserNotificationRepositoryContract $UserNotification
     */
    public function __construct(UserRepository $users, UserTransformer $userTransformer) {
        $this->users = $users;
        $this->userTransformer = $userTransformer;
    }

    /**
     * register user from app
     * @param Request $request
     * @return type
     */
    public function register(Request $request) {
        $postData = $request->all();
        dd($postData);
        if (isset($postData['email']) && $postData['email'] &&
                isset($postData['password']) && $postData['password'] &&
                isset($postData['name']) && $postData['name'] &&
                $request->header('DeviceToken') &&
                $request->header('Type')
        ) {
            if (!$this->users->checkEmailAlreadyExist($postData['email'])) {
                return $this->respondInternalError('User\'s email Already Exist');
            }

            if (!$this->users->checkMobileAlreadyExist($postData['MobileNumber'])) {
                return $this->respondInternalError('User\'s Mobile  Already Exist');
            }

            $user = $this->users->createAppUser($postData);
            //check user is created 
            if ($user) {
                $this->setStatusCode(200);
                $userData = $this->users->fetchUserByField('users.id', $user->id)->first();
                $userData = $userData->toArray();
                if (isset($postData['PushToken']) && $postData['PushToken']) {
                    $newUserToken = $this->users->updateUserToken($userData['id'], $request->header('Type'), $request->header('DeviceToken'), $postData['PushToken']);
                } else {
                    $newUserToken = $this->users->updateUserToken($userData['id'], $request->header('Type'), $request->header('DeviceToken'));
                }
                $userData['user_token'] = $newUserToken;
                $otp = $userData['otp'] . " is your Medicus Verification Code.It is valid for 15 minutes.Please do not share with anyone.";
                $mobileNumber = $userData['mobile_number'];
                // send otp to mobile number  
                $url = "http://sms.hspsms.com:/sendSMS";
                $ch = \curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=MEDICUS&message=' . $otp . '&sendername=MEDCUS&smstype=TRANS&numbers=' . $mobileNumber . '&apikey=47958623-ebc8-4bf7-a854-4fa97408bf13');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);

                $responseData = $this->userTransformer->transform($userData);
                return $this->ApiSuccessResponse($responseData);
            } else {
                return $this->respondInternalError('Invalid Arguments');
            }
        } else {
            return $this->respondInternalError('Invalid Arguments');
        }
    }

    /**
     * Login request
     * @param Request $request
     * @return type
     */
    public function login(Request $request) {
//        $postData = $request->all();
        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);
        if (isset($postData['MobileOrEmail']) && $postData['MobileOrEmail'] &&
                isset($postData['password']) && $postData['password'] &&
                $request->header('DeviceToken') &&
                $request->header('Type')
        ) {
            $emailUser = $this->users->fetchUserByField('email', $postData['MobileOrEmail'])->first();
            if ($emailUser) {
                $user = $emailUser;
            } else {
                $mobileUser = $this->users->fetchUserByField('mobile_number', $postData['MobileOrEmail'])->first();
                if ($mobileUser) {
                    $user = $mobileUser;
                } else {
                    $user = null;
                }
            }

            if ($user) {
                if ($user->status != 0) {
                    if (Hash::check($postData['password'], $user->password)) {
                        $user = $user->toArray();
                        $this->setStatusCode(200);
                        if (isset($postData['PushToken']) && $postData['PushToken']) {
                            $newUserToken = $this->users->updateUserToken($user['id'], $request->header('Type'), $request->header('DeviceToken'), $postData['PushToken']);
                        } else {
                            $newUserToken = $this->users->updateUserToken($user['id'], $request->header('Type'), $request->header('DeviceToken'));
                        }
                        $user['user_token'] = $newUserToken;
                        $responseData = $this->userTransformer->transform($user);

                        return $this->ApiSuccessResponse($responseData);
                    } else {
                        return $this->respondInternalError('credentials doesn not match');
                    }
                } else {
                    return $this->respondInternalError('User Inactive');
                }
            } else {
                return $this->respondInternalError('No Such User found');
            }
        } else {
            return $this->respondInternalError('Invalid Arguments');
        }
    }

    /**
     * Logout request
     * @param  Request $request
     * @return json
     */
    public function logout(Request $request) {
        $userId = $request->header('UserId');
        $userToken = $request->header('UserToken');
        $response = $this->users->deleteUserToken($userId, $userToken);
        if ($response) {
            return $this->ApiSuccessResponse(array());
        } else {
            return $this->respondInternalError('Error in Logout');
        }
    }

    /**
     * changePassword
     * 
     * @param Request $request
     * @return type
     */
    public function changePassword(Request $request) {
//        $postData = $request->all();
        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);
        $result = $this->users->changePassword($postData);

        if ($result == 1) {
            $this->setStatusCode(200);
            return $this->ApiSuccessResponse("Success");
        } else if ($result == 3) {
            $this->setStatusCode(200);
            return $this->ApiSuccessResponse("Failure");
        } else if ($result == 2) {
            return $this->respondInternalError('User not found!');
        } else if ($result == 4) {
            return $this->respondInternalError('OldPassword do not match with current password!');
        }
    }

    /**
     * forgotPassword
     * 
     * @param Request $request
     * @return type
     */
    public function forgotPassword(Request $request) {
//        $postData = $request->all();
        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);
        $result = $this->users->forgotPassword($postData);

        if ($result == 1) {
            $this->setStatusCode(200);
            return $this->ApiSuccessResponse("Success");
        } else if ($result == 3) {
            $this->setStatusCode(200);
            return $this->ApiSuccessResponse("Failure");
        } else if ($result == 2) {
            return $this->respondInternalError('User not found!');
        } else if ($result == 4) {
            return $this->respondInternalError('New password email not sent to user!');
        }
    }
}
