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
 * Users
 */

Route::group(['prefix' => 'users'], function (){
    Route::get('/', 'User\UserController@index')->name('users.index');
    Route::get('/id', 'User\UserController@find')->name('users.find');
    Route::post('/update', 'User\UserController@update')->name('users.update');
    Route::put('/put', 'User\UserController@update')->name('users.put');
    Route::delete('/delete', 'User\UserController@destroy')->name('users.delete');
    Route::post('/create', 'User\UserController@store')->name('users.create');
});


/**
 * Categories
 */

Route::group(['prefix' => 'categories'], function (){
    Route::get('/', 'Category\CategoryController@index')->name('categories.index');
    Route::get('/id', 'Category\CategoryController@find')->name('categories.find');
    Route::post('/update', 'Category\CategoryController@update')->name('categories.update');
    Route::put('/put', 'Category\CategoryController@update')->name('categories.put');
    Route::delete('/delete', 'Category\CategoryController@destroy')->name('categories.delete');
    Route::post('/create', 'Category\CategoryController@store')->name('categories.create');
});

/**
 * UserTypes
 */

Route::group(['prefix' => 'usersTypes'], function (){
    Route::get('/', 'UserType\UserTypeController@index')->name('users_types.index');
    Route::get('/id', 'UserType\UserTypeController@find')->name('users_types.find');
    Route::post('/update', 'UserType\UserTypeController@update')->name('users_types.update');
    Route::put('/put', 'UserType\UserTypeController@update')->name('users_types.put');
    Route::delete('/delete', 'UserType\UserTypeController@destroy')->name('users_types.delete');
    Route::post('/create', 'UserType\UserTypeController@store')->name('users_types.create');
});


/**
 * RequestTypes
 */

Route::group(['prefix' => 'requestsTypes'], function (){
    Route::get('/', 'RequestType\RequestTypeController@index')->name('requests_types.index');
    Route::get('/id', 'RequestType\RequestTypeController@find')->name('requests_types.find');
    Route::post('/update', 'RequestType\RequestTypeController@update')->name('requests_types.update');
    Route::put('/put', 'RequestType\RequestTypeController@update')->name('requests_types.put');
    Route::delete('/delete', 'RequestType\RequestTypeController@destroy')->name('requests_types.delete');
    Route::post('/create', 'RequestType\RequestTypeController@store')->name('requests_types.create');
});

/**
 * Requests
 */

Route::group(['prefix' => 'requests'], function (){
    Route::get('/', 'Request\RequestController@index')->name('requests.index');
    Route::get('/id', 'Request\RequestController@find')->name('requests.find');
    Route::post('/update', 'Request\RequestController@update')->name('requests.update');
    Route::put('/put', 'Request\RequestController@update')->name('requests.put');
    Route::delete('/delete', 'Request\RequestController@destroy')->name('requests.delete');
    Route::post('/create', 'Request\RequestController@store')->name('requests.create');
});

/**
 * InternalClients
 */

Route::group(['prefix' => 'internalClients'], function (){
    Route::get('/', 'InternalClient\InternalClientController@index')->name('internalClients.index');
    Route::get('/id', 'InternalClient\InternalClientController@find')->name('internalClients.find');
    Route::post('/update', 'InternalClient\InternalClientController@update')->name('internalClients.update');
    Route::put('/put', 'InternalClient\InternalClientController@update')->name('internalClients.put');
    Route::delete('/delete', 'InternalClient\InternalClientController@destroy')->name('internalClients.delete');
    Route::post('/create', 'InternalClient\InternalClientController@store')->name('internalClients.create');
});

/**
 * ExternalClients
 */

Route::group(['prefix' => 'externalClients'], function (){
    Route::get('/', 'ExternalClient\ExternalClientController@index')->name('externalClients.index');
    Route::get('/id', 'ExternalClient\ExternalClientController@find')->name('externalClients.find');
    Route::post('/update', 'ExternalClient\ExternalClientController@update')->name('externalClients.update');
    Route::put('/put', 'ExternalClient\ExternalClientController@update')->name('externalClients.put');
    Route::delete('/delete', 'ExternalClient\ExternalClientController@destroy')->name('externalClients.delete');
    Route::post('/create', 'ExternalClient\ExternalClientController@store')->name('externalClients.create');
});


