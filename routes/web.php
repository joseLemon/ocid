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

/*Route::get('register', function () {
    return redirect('/user/create');
});*/
/*Route::post('register', function () {
    abort(404);
});*/

//  Public web, but avoid access when logged.
Route::group(['middleware' => '\App\Http\Middleware\Authenticate'], function () {
    Route::get('/', [
        'as' => 'home',
        'uses' => 'HomeController@index'
    ]);

    Route::get('/home', function () {
        return redirect('/');
    });

    //--- USERS SECTION ---
    include('web/users.php');
    //--- SERVICES SECTION ---
    include('web/services.php');
    //--- CUSTOMERS SECTION ---
    include('web/customers.php');
    //--- BRANCHES SECTION ---
    include('web/branches.php');
    //--- DOCTORS SECTION ---
    include('web/doctors.php');
    //--- APPOINTMENTS SECTION ---
    include('web/appointments.php');
    //--- CALENDAR SECTION ---
    include('web/calendar.php');

    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

});
