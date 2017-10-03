<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api',], function () 
{
    Route::post('login', 'UsersController@login')->name('api.login');
    Route::post('register', 'UsersController@register')->name('api.register');
    Route::post('fblogin', 'UsersController@facebookLogin')->name('api.fblogin');
    Route::post('checkusername', 'UsersController@checkUserName')->name('api.checkusername');
});

Route::group(['namespace' => 'Api', 'middleware' => 'jwt.customauth'], function () 
{
    Route::get('events', 'EventsController@index')->name('api.events');


    Route::get('sporty-fans', 'APIFansController@index')->name('fans.index');



    Route::group(['prefix' => 'users', 'as' => 'users.'], function () 
    {
        Route::get('getdata', 'UsersController@getData')->name('api.getdata');
        Route::post('update', 'UsersController@update')->name('api.update');
        Route::post('getlist', 'UsersController@getList')->name('api.getlist');
        Route::post('follow', 'UsersController@follow')->name('api.follow');
        Route::post('unfollow', 'UsersController@unFollow')->name('api.unfollow');

        Route::get('get-fans', 'UsersController@getFanData')->name('fan.getdata');
        Route::get('get-news', 'UsersController@getNewsData')->name('news.getdata');

    });

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () 
    {
        Route::get('getlist', 'PostsController@getList')->name('api.getlist');

        Route::get('getpost-by-id', 'PostsController@getSingleItem')->name('api.get-single-post');

        Route::get('getlist', 'PostsController@getList')->name('api.getlist');
        Route::post('getdata', 'PostsController@getData')->name('api.getlist');
        Route::post('create', 'PostsController@create')->name('api.create');
        Route::post('update', 'PostsController@update')->name('api.update');
        Route::post('delete', 'PostsController@delete')->name('api.delete');
        Route::post('like', 'PostsController@like')->name('api.like');
        Route::post('unlike', 'PostsController@unLike')->name('api.unlike');
        Route::post('add-comment', 'PostsController@createComment')->name('api.create-comment');
        Route::post('delete-comment', 'PostsController@deleteComment')->name('api.delete-comment');
    });
});