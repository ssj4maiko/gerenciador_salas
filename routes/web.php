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

	Route::get('home'				,array('uses' => 'ReservaController@index'));
	
	Route::get('usuario/{id}'		,array('uses' => 'UsuarioController@create'));
	Route::post('usuario/save/{id}' ,array('uses' => 'UsuarioController@save'));
	Route::get('usuario/del/{id}'	,array('uses' => 'UsuarioController@del'));

});

Route::get('login'				,array('uses' => 'HomeController@showLogin'));
Route::get('registrar/'			,array('uses' => 'UsuarioController@create'));
Route::post('registrar/save/'	,array('uses' => 'UsuarioController@save'));
Route::get('logout'				,array('uses' => 'HomeController@logOut'));