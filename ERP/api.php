<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1/crm/'], function() {

    //Bodegas
    Route::group(['prefix' => 'clasificacionesclientes/'], function() {
        Route::get('/', 'ClasificacionClienteController@index')->name('clasificacionesclientes.index');
        Route::get('/id', 'ClasificacionClienteController@find')->name('clasificacionesclientes.find');
        Route::post('/update', 'ClasificacionClienteController@update')->name('clasificacionesclientes.update');
        Route::put('/put', 'ClasificacionClienteController@update')->name('clasificacionesclientes.put');
        Route::delete('/delete', 'ClasificacionClienteController@destroy')->name('clasificacionesclientes.delete');
        Route::post('/create', 'ClasificacionClienteController@store')->name('clasificacionesclientes.create');
    });

    //Terceros Clientes
    Route::group(['prefix' => 'clientes/'], function(){
        Route::get('/', 'TerceroClienteController@index')->name('clientes.index');
        Route::get('/id', 'TerceroClienteController@find')->name('clientes.find');
        Route::post('/update', 'TerceroClienteController@update')->name('clientes.update');
        Route::put('/put', 'TerceroClienteController@update')->name('clientes.put');
        Route::delete('/delete', 'TerceroClienteController@destroy')->name('clientes.delete');
        Route::post('/create', 'TerceroClienteController@store')->name('clientes.create');
    });

    //Temporadas
    Route::group(['prefix' => 'temporadas/'], function(){
        Route::get('/', 'TemporadaController@index')->name('temporadas.index');
        Route::get('/id', 'TemporadaController@find')->name('temporadas.find');
        Route::post('/update', 'TemporadaController@update')->name('temporadas.update');
        Route::put('/put', 'TemporadaController@update')->name('temporadas.put');
        Route::delete('/delete', 'TemporadaController@destroy')->name('temporadas.delete');
        Route::post('/create', 'TemporadaController@store')->name('temporadas.create');
    });

    //Terceros Sucursales
    Route::group(['prefix' => 'tercerossucursales/'], function(){
        Route::get('/', 'TerceroSucursalController@index')->name('tercerossucursales.index');
        Route::get('/id', 'TerceroSucursalController@find')->name('tercerossucursales.find');
        Route::post('/update', 'TerceroSucursalController@update')->name('tercerossucursales.update');
        Route::put('/put', 'TerceroSucursalController@update')->name('tercerossucursales.put');
        Route::delete('/delete', 'TerceroSucursalController@destroy')->name('tercerossucursales.delete');
        Route::post('/create', 'TerceroSucursalController@store')->name('tercerossucursales.create');
    });
});