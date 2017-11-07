<?php

/**
 * All route names are prefixed with 'admin.'.
 */
Route::get('gif', 'AdminGifController@index')->name('gif');


Route::group([
    'namespace'  => 'Gif',
], function () {

    /*
     * Admin Gif Controller
     */
    Route::resource('gif', 'AdminGifController');

    Route::get('/', 'AdminGifController@index')->name('gif.index');
    Route::get('/get', 'AdminGifController@getTableData')->name('gif.get-data');
});
