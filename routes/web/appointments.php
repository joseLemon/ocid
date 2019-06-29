<?php

Route::group(['middleware' => ['can:read-appointments']], function () {
    Route::get('/appointments', [
        'as' => 'appointments',
        'uses' => 'AppointmentController@index'
    ]);
    Route::get('/appointments/search', [
        'as' => 'appointments.search',
        'uses' => 'AppointmentController@search'
    ]);
});

Route::group(['middleware' => ['can:create-appointments']], function () {
    Route::get('/appointment', [
        'as' => 'appointment',
        'uses' => 'AppointmentController@create'
    ]);
    Route::post('/appointment', [
        'as' => 'appointment.store',
        'uses' => 'AppointmentController@store'
    ]);
});

Route::group(['middleware' => ['can:update-appointments']], function () {
    Route::get('/appointment/{id}', [
        'as' => 'appointment.edit',
        'uses' => 'AppointmentController@edit'
    ]);
    Route::post('/appointment/update/{id}', [
        'as' => 'appointment.update',
        'uses' => 'AppointmentController@update'
    ]);
});

Route::group(['middleware' => ['can:delete-appointments']], function () {
    Route::post('/appointment/delete', [
        'as' => 'appointment.delete',
        'uses' => 'AppointmentController@edit'
    ]);
});
