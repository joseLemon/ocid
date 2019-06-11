<?php

Route::group(['middleware' => ['can:read-customers']], function () {
    Route::get('/customers/search', [
        'as' => 'customers.search',
        'uses' => 'CustomerController@search'
    ]);
});

Route::group(['middleware' => ['can:create-customers']], function () {
    Route::post('/customer', [
        'as' => 'customer.store',
        'uses' => 'CustomerController@store'
    ]);
});
