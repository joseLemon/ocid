<?php

Route::group(['middleware' => ['can:read-users']], function () {
    Route::get('/users', [
        'as' => 'users',
        'uses' => 'UserController@index'
    ]);
    Route::get('/users/search', [
        'as' => 'users.search',
        'uses' => 'UserController@search'
    ]);
});

Route::group(['middleware' => ['can:create-users']], function () {
    Route::get('/user', [
        'as' => 'user',
        'uses' => 'UserController@create'
    ]);
    Route::post('/user', [
        'as' => 'user.store',
        'uses' => 'UserController@store'
    ]);
});

Route::group(['middleware' => ['can:update-users']], function () {
    Route::get('/user/{id}', [
        'as' => 'user.edit',
        'uses' => 'UserController@edit'
    ]);
    Route::post('/user/update/{id}', [
        'as' => 'user.update',
        'uses' => 'UserController@update'
    ]);
});

Route::group(['middleware' => ['can:delete-users']], function () {
    Route::post('/user/delete', [
        'as' => 'user.delete',
        'uses' => 'UserController@edit'
    ]);
});
