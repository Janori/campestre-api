<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::group(['middleware' => ['cors']], function(){

	Route::post('authenticate', 'AuthenticateController@authenticate');
	Route::get('users/loggedin', 'AuthenticateController@isLogged');
	Route::get('debtors', 'MemberController@debtors');
	Route::group(['middleware' => ['jwt.auth']], function(){
		Route::group(['prefix'=>'search'], function(){
		});
		Route::group(['prefix'=>'download'], function(){
		});
		Route::group(['prefix'=>'employees'], function(){
			Route::get('', 'MemberController@employees');
		});
		Route::group(['prefix'=>'guests'], function(){
			Route::get('', 'MemberController@guests');
			Route::post('{idmember}/setrel/{idref}', 'MemberController@relGuest');
			Route::put('{idmember}/unsetrel/{idref}', 'MemberController@unrelGuest');
		});
		Route::group(['prefix'=>'associates'], function(){
			Route::get('', 'MemberController@associates');
		});
		Route::resource('users', 'UserController');
		Route::put('members/{idmember}/setrel/{idref}', 'MemberController@setRel');
		Route::put('members/{idmember}/unsetrel', 'MemberController@unsetRel');
		Route::put('members/delfmd/{id}', 'MemberController@deleteFMD');
		Route::get('members/{id}/historial', 'MemberController@historial');
		Route::resource('members', 'MemberController');
	});
});
