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
Route::get('register', 'HomeController@register');
Route::get('books', 'BookController@index');
Route::get('movies', 'MovieController@index');
Route::get('games', 'GameController@index');
Route::get('songs', 'SongController@index');

Route::get('readlater', 'LinkController@readLater');

Route::group(array('prefix' => 'api'), function() {
  Route::post('register', 'UserController@processRegistration');
  Route::post('checkusername', 'UserController@checkUsername');
  Route::post('checkemail', 'UserController@checkEmail');
  Route::post('login', 'UserController@login');
  Route::get('dashboard', 'HomeController@getDashboard');
  Route::group(array('before' => 'auth'), function() {
    Route::post('logout', 'UserController@logout');
    Route::group(array('prefix' => 'link'), function(){
      Route::post('save', 'LinkController@save');
      Route::post('search', 'LinkController@search');
      Route::post('open/{id}', 'LinkController@open');
      Route::post('read/{id}', 'LinkController@read');
      Route::post('unread/{id}', 'LinkController@unread');
      Route::post('delete/{id}', 'LinkController@delete');
      Route::get('dashboard/{category}', 'LinkController@getRandomLinks');
      Route::get('dashboard/{category}/{quantity}', 'LinkController@getRandomLinks');
      Route::get('populate', 'LinkController@populateLinks');
    });
    Route::group(array('prefix' => 'book'), function(){
      Route::get('/', 'BookController@query');
      Route::post('/', 'BookController@save');
      Route::delete('/', 'BookController@delete');
      Route::get('recommendation/{category}', 'BookController@recommendation');
    });
    Route::group(array('prefix' => 'movie'), function(){
      Route::get('', 'MovieController@all');
      Route::get('widget', 'MovieController@widget');
      Route::post('save', 'MovieController@save');
      Route::post('delete/{id}', 'MovieController@delete');
    });
    Route::group(array('prefix' => 'game'), function(){
      Route::get('/', 'GameController@query');
      Route::post('/', 'GameController@save');
      Route::delete('/', 'GameController@delete');
      Route::get('recommendation', 'GameController@recommend');
    });
    Route::group(array('prefix' => 'song'), function(){
      Route::get('/', 'SongController@query');
      Route::post('/', 'SongController@save');
      Route::delete('/', 'SongController@delete');
    });
  });
});

Route::group(array('prefix' => 'templates'), function(){
  Route::get('link-form', 'TemplateController@linkForm');
  Route::get('book-form', 'TemplateController@bookForm');
  Route::get('movie-form', 'TemplateController@movieForm');
  Route::get('game-form', 'TemplateController@gameForm');
  Route::get('song-form', 'TemplateController@songForm');
  Route::get('loader', 'TemplateController@loader');
});

Route::group(array('prefix' => 'migrations', 'before' => 'auth'), function(){
  Route::get('books', 'MigrationsController@books');
  Route::get('games', 'MigrationsController@games');
  Route::get('links', 'MigrationsController@links');
  Route::get('movies', 'MigrationsController@movies');
  Route::get('movies/randomize', 'MigrationsController@reorder');
});
