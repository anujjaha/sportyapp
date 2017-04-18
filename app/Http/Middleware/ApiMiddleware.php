<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\UserRepository;

use Closure;

class ApiMiddleware
{
    
    public function __construct(UserRepository $userRepository,Controller $controller){
        $this->userRepository = $userRepository;
        $this->controller = $controller;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = getallheaders();        
        if(isset($headers['UserId']) && $headers['UserId'] && isset($headers['UserToken']) && $headers['UserToken']){
            $check = $this->userRepository->checkUserToken($headers['UserId'],$headers['UserToken']);                
            if($check->count()){
                return $next($request);
            } else {
                return $this->controller->respondInternalError('Invalid UserToken');
            }
        } else {
            return $this->controller->respondInternalError('Invalid Request');
        }      
    }
}
