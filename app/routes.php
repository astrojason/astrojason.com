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
  Route::post('/register', 'ApiController@processRegistration');
  Route::post('/checkusername', 'ApiController@checkUsername');
  Route::post('/checkemail', 'ApiController@checkEmail');
  Route::post('/login', 'ApiController@login');
  Route::post('/logout', 'ApiController@logout');
});