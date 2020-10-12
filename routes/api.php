<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//unprotected routes
Route::group(['prefix' => 'v1', 'middleware' => []], function () {    
    
    Route::post('/auth/login','TokensController@login');   

});


//protected routes
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify']], function () {
       
    Route::post('/auth/refresh','TokensController@refreshToken');
    Route::get('/auth/logout','TokensController@logout');


    Route::resource('third-party','ThirdPartyController');
    Route::resource('city','CityController');
    
    Route::resource('vehicle-type','VehicleTypeController');
    Route::resource('vehicle','VehicleController');


});