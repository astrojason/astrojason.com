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

Route::get('', 'HomeController@showIndex');
Route::get('info', function(){
  return View::make('info');
});

Route::group(['before' => 'auth'], function(){
  Route::get('games', 'GameController@index');
  Route::get('links', 'LinkController@index');
  Route::get('movies', 'MovieController@index');
  Route::get('register', 'HomeController@register');
  Route::get('songs', 'SongController@index');
  Route::group(['prefix' => 'account'], function(){
    Route::get('', 'UserController@account');
    Route::post('', 'UserController@update');
  });
});

Route::get('readlater', 'LinkController@readLater');

Route::group(['prefix' => 'books'], function(){
  @include('routes/books.php');
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
  @include('routes/api.php');
});

Route::group(array('prefix' => 'templates'), function(){
  @include('routes/templates.php');
});