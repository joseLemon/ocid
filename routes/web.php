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

Route::get('register', function () {
    return redirect('/user/create');
});
Route::post('register', function () {
    abort(404);
});

//  Public web, but avoid access when logged.
Route::group(['middleware' => '\App\Http\Middleware\Authenticate'], function () {

    Route::get('/', function () {
        return view('calendar.index');
    });
    Route::get('/home', function () {
        return redirect('/');
    });

    Route::get('/doctor', function () {
        return view('doctors.create');
    });

    //--- USERS SECTION ---
    include('web/users.php');

});
