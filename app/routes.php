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

Route::get('/', 'MainController@showMain');

Route::group(array('prefix' => 'api'), function() {
  Route::get('/books', 'ApiController@allBooks');

  Route::group(array('prefix' => 'book'), function(){
    Route::get('/next', 'ApiController@nextBook');
    Route::get('/{id}/read/', 'ApiController@markBookAsRead');
  });

  Route::group(array('prefix' => 'links'), function(){
    Route::get('/', 'ApiController@allLinks');
    Route::get('/today', 'ApiController@todaysLinks');
  });

  Route::group(array('prefix' => 'link'), function(){
    Route::put('/', 'ApiController@saveLink');
  });

  Route::post('/login', 'ApiController@login');

  Route::group(array('prefix' => 'movies'), function(){
    Route::get('/', 'ApiController@allMovies');
    Route::get('/compare', 'ApiController@compareMovies');
  });
});