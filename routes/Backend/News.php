<?php

Route::group([
    'namespace'  => 'News',
], function () {

    /*
     * Admin Gif Controller
     */
    Route::resource('news', 'AdminNewsController');

    Route::get('/', 'AdminNewsController@index')->name('news.index');
    Route::get('get', 'AdminNewsController@getTableData')->name('news.get-data');
});


