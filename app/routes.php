<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showIndex');

Route::group(array('prefix' => 'api'), function() {
  Route::post('/register', 'UserController@processRegistration');
  Route::post('/checkusername', 'UserController@checkUsername');
  Route::post('/checkemail', 'UserController@checkEmail');
  Route::post('/login', 'UserController@login');
  Route::post('/logout', 'UserController@logout');
  Route::group(array('prefix' => 'links'), function(){
    Route::post('/add', 'LinksController@add');
  });
});

Route::group(array('prefix' => 'templates'), function(){
  Route::get('/link-form', 'TemplateController@linkForm');
});