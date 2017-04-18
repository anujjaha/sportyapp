<?php

Route::group(['namespace' => 'Api', 'prefix' => 'api', 'as' => 'api.'], function () {
    Route::post('login', 'UsersController@login')->name('login');
    Route::post('register', 'UsersController@register')->name('register');    
    Route::post('forgotpassword', 'UsersController@forgotPassword')->name('forgotPassword');    
});

Route::group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => 'authAPI', 'as' => 'api.'], function () {
    
});
