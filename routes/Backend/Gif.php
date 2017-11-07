<?php

Route::group([
    'namespace'  => 'Gif',
], function () {

    /*
     * Admin Gif Controller
     */
    Route::resource('gifs', 'AdminGifController');

    Route::get('/', 'AdminGifController@index')->name('gifs.index');
    Route::any('gif/web', 'AdminGifController@getTableData')->name('gifs.custom-route');
});

?>