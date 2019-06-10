<?php

Route::group(['middleware' => ['can:read-services']], function () {
    Route::get('/services', [
        'as' => 'services',
        'uses' => 'ServiceController@index'
    ]);
    Route::get('/services/search', [
        'as' => 'services.search',
        'uses' => 'ServiceController@search'
    ]);
});

Route::group(['middleware' => ['can:create-services']], function () {
    Route::get('/service', [
        'as' => 'service',
        'uses' => 'ServiceController@create'
    ]);
    Route::post('/service', [
        'as' => 'service.store',
        'uses' => 'ServiceController@store'
    ]);
});

Route::group(['middleware' => ['can:update-services']], function () {
    Route::get('/service/{id}', [
        'as' => 'service.edit',
        'uses' => 'ServiceController@edit'
    ]);
    Route::post('/service/update/{id}', [
        'as' => 'service.update',
        'uses' => 'ServiceController@update'
    ]);
});

Route::group(['middleware' => ['can:delete-services']], function () {
    Route::post('/service/delete', [
        'as' => 'service.delete',
        'uses' => 'ServiceController@edit'
    ]);
});
