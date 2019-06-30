<?php

Route::get('/home/getCalendarData', [
    'as' => 'home.getCalendarData',
    'uses' => 'HomeController@getCalendarData'
]);
