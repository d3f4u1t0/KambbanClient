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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
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
 * Companies
 */

Route::group(['prefix' => 'companies'], function (){
    Route::get('/', 'CompanyController@index')->name('companies.index');
    Route::get('/id', 'CompanyController@find')->name('companies.find');
    Route::post('/update', 'CompanyController@update')->name('companies.update');
    Route::put('/put', 'CompanyController@update')->name('companies.put');
    Route::delete('/delete', 'CompanyController@destroy')->name('companies.delete');
    Route::post('/create', 'CompanyController@store')->name('companies.create');
});


/**
 * Categories
 */

Route::resource('categories', 'Category\CategoryController', ['only' => ['index', 'show','store', 'update', 'destroy']]);

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
 * ExternalCustomer
 */

Route::resource('externalCustomers', 'ExternalCustomer\ExternalCustomerController', ['only' => ['index', 'show', 'store', 'update','destroy']]);
