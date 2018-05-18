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

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => 'auth'], function(){

	Route::post('login'				,array('uses' => 'HomeController@doLogin'));
});

Route::get('login'				,array('uses' => 'HomeController@showLogin'));
Route::get('registrar/'			,array('uses' => 'UsuarioController@create'));
Route::post('registrar/save/'	,array('uses' => 'UsuarioController@save'));
Route::get('logout'				,array('uses' => 'HomeController@logOut'));