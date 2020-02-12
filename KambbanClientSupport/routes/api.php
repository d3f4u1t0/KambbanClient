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

Route::resource('users', 'User\UserController', ['only' => ['index', 'find', 'store', 'update','destroy']]);

/**
 * Companies
 */

Route::resource('companies', 'Company\CompanyController', ['only' => ['index', 'show','store','update', 'destroy']]);

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
