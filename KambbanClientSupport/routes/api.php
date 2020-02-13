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
    Route::get('/', 'Company\CompanyController@index')->name('companies.index');
    Route::get('/id', 'Company\CompanyController@find')->name('companies.find');
    Route::post('/update', 'Company\CompanyController@update')->name('companies.update');
    Route::put('/put', 'Company\CompanyController@update')->name('companies.put');
    Route::delete('/delete', 'Company\CompanyController@destroy')->name('companies.delete');
    Route::post('/create', 'Company\CompanyController@store')->name('companies.create');
});

/**
 * Categories
 */

Route::resource('categories', 'Category\CategoryController', ['only' => ['index', 'show','store', 'update', 'destroy']]);

/**
 * UserTypes
 */

Route::resource('userTypes', 'UserType\UserTypeController', ['only' => ['index', 'show', 'store', 'update','destroy']]);

/**
 * RequestTypes
 */

Route::resource('requestTypes', 'RequestType\RequestTypeController', ['only' => ['index', 'show', 'store', 'update','destroy']]);

/**
 * ExternalCustomer
 */

Route::resource('externalCustomers', 'ExternalCustomer\ExternalCustomerController', ['only' => ['index', 'show', 'store', 'update','destroy']]);
