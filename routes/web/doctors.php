<?php

Route::group(['middleware' => ['can:read-users']], function () {
    Route::get('/doctors', [
        'as' => 'doctors',
        'uses' => 'DoctorController@index'
    ]);
    Route::get('/doctors/search', [
        'as' => 'doctors.search',
        'uses' => 'DoctorController@search'
    ]);
});

Route::group(['middleware' => ['can:create-users']], function () {
    Route::get('/doctor', [
        'as' => 'doctor',
        'uses' => 'DoctorController@create'
    ]);
    Route::post('/doctor', [
        'as' => 'doctor.store',
        'uses' => 'DoctorController@store'
    ]);
});

Route::group(['middleware' => ['can:update-users']], function () {
    Route::get('/doctor/{id}', [
        'as' => 'doctor.edit',
        'uses' => 'DoctorController@edit'
    ]);
    Route::post('/doctor/update/{id}', [
        'as' => 'doctor.update',
        'uses' => 'DoctorController@update'
    ]);
});

Route::group(['middleware' => ['can:delete-users']], function () {
    Route::post('/doctor/delete', [
        'as' => 'doctor.delete',
        'uses' => 'DoctorController@edit'
    ]);
});
