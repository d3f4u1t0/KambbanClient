<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

/**
 * Requests
 */

Route::group(['prefix' => 'requests', 'middleware' => 'auth:api'], function (){
    Route::get('/', 'Request\RequestController@index')->name('requests.index');
    Route::post('/update', 'Request\RequestController@update')->name('requests.update');
    Route::post('/create', 'Request\RequestController@store')->name('requests.create');
});

