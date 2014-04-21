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
Route::get('/links', 'MainController@showLinks');
Route::get('/books', 'MainController@showBooks');

Route::get('/logout', 'MainController@logout');

Route::group(array('prefix' => 'api'), function() {
  Route::get('/books', 'ApiController@allBooks');

  Route::group(array('prefix' => 'book'), function(){
    Route::get('/next', 'ApiController@nextBook');
    Route::get('/categories', 'ApiController@bookCategories');
    Route::get('/{id}/read', 'ApiController@markBookAsRead');
    Route::put('/', 'ApiController@saveBook');
  });

  Route::group(array('prefix' => 'links'), function(){
    Route::get('/', 'ApiController@allLinks');
    Route::get('/today', 'ApiController@todaysLinks');
    Route::get('/{query}', 'ApiController@filterLinks');
    Route::get('/{category}/{quantity}', 'ApiController@getRandomLinksAction');
  });

  Route::group(array('prefix' => 'link'), function(){
    Route::put('/', 'ApiController@saveLink');
    Route::put('/add', 'ApiController@addLinkFromBookmarklet');
    Route::get('/categories', 'ApiController@linkCategories');
    Route::get('/{id}/read', 'ApiController@markLinkAsRead');
  });

  Route::post('/login', 'ApiController@login');

  Route::group(array('prefix' => 'movies'), function(){
    Route::get('/', 'ApiController@allMovies');
    Route::get('/compare', 'ApiController@compareMovies');
  });
});