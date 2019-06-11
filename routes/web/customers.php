<?php

Route::group(['middleware' => ['can:read-customers']], function () {
    Route::get('/customers', [
        'as' => 'customers',
        'uses' => 'CustomerController@index'
    ]);
    Route::get('/customers/search', [
        'as' => 'customers.search',
        'uses' => 'CustomerController@search'
    ]);
    Route::get('/customers/searchSelect', [
        'as' => 'customers.searchSelect',
        'uses' => 'CustomerController@searchSelect'
    ]);
});

Route::group(['middleware' => ['can:create-customers']], function () {
    Route::get('/customer', [
        'as' => 'customer',
        'uses' => 'CustomerController@create'
    ]);
    Route::post('/customer', [
        'as' => 'customer.store',
        'uses' => 'CustomerController@store'
    ]);
});

Route::group(['middleware' => ['can:update-customers']], function () {
    Route::get('/customer/{id}', [
        'as' => 'customer.edit',
        'uses' => 'CustomerController@edit'
    ]);
    Route::post('/customer/update/{id}', [
        'as' => 'customer.update',
        'uses' => 'CustomerController@update'
    ]);
});

Route::group(['middleware' => ['can:delete-customers']], function () {
    Route::post('/customer/delete', [
        'as' => 'customer.delete',
        'uses' => 'CustomerController@edit'
    ]);
});

