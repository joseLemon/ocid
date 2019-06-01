<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Route::group(['middleware' => ['can:create-users']], function () {
    Route::get('register', [
        'as' => 'register',
        'uses' => 'Auth\RegisterController@showRegistrationForm'
    ]);
//});


//  Public web, but avoid access when logged.
Route::group(['middleware' => '\App\Http\Middleware\Authenticate'], function () {

    Route::get('/', function () {
        return view('calendar.index');
    });

    Route::get('/doctor', function () {
        return view('doctors.create');
    });

});
