<?php

Route::group(['middleware' => ['can:read-branches']], function () {
    Route::get('/branches', [
        'as' => 'branches',
        'uses' => 'BranchController@index'
    ]);
    Route::get('/branches/search', [
        'as' => 'branches.search',
        'uses' => 'BranchController@search'
    ]);
});

Route::group(['middleware' => ['can:create-branches']], function () {
    Route::get('/branch', [
        'as' => 'branch',
        'uses' => 'BranchController@create'
    ]);
    Route::post('/branch', [
        'as' => 'branch.store',
        'uses' => 'BranchController@store'
    ]);
});

Route::group(['middleware' => ['can:update-branches']], function () {
    Route::get('/branch/{id}', [
        'as' => 'branch.edit',
        'uses' => 'BranchController@edit'
    ]);
    Route::post('/branch/update/{id}', [
        'as' => 'branch.update',
        'uses' => 'BranchController@update'
    ]);
});

Route::group(['middleware' => ['can:delete-branches']], function () {
    Route::post('/branch/delete', [
        'as' => 'branch.delete',
        'uses' => 'BranchController@edit'
    ]);
});
