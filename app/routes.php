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
Route::get('/register', 'HomeController@register');

Route::get('/readlater', 'LinkController@readLater');

Route::group(array('prefix' => 'api'), function() {
  Route::post('/register', 'UserController@processRegistration');
  Route::post('/checkusername', 'UserController@checkUsername');
  Route::post('/checkemail', 'UserController@checkEmail');
  Route::post('/login', 'UserController@login');
  Route::group(array('before' => 'auth'), function() {
    Route::post('/logout', 'UserController@logout');
    Route::group(array('prefix' => 'links'), function(){
      Route::post('/save', 'LinkController@save');
      Route::post('/search', 'LinkController@search');
      Route::post('/open/{id}', 'LinkController@open');
      Route::post('/read/{id}', 'LinkController@read');
      Route::post('/unread/{id}', 'LinkController@unread');
      Route::post('/delete/{id}', 'LinkController@delete');
      Route::get('/dashboard', 'LinkController@getDashboard');
      Route::get('/dashboard/{category}', 'LinkController@getRandomLinks');
      Route::get('/dashboard/{category}/{quantity}', 'LinkController@getRandomLinks');
    });
    Route::group(array('prefix' => 'books'), function(){
      Route::get('/recommendation/{category}', 'BookController@recommendation');
      Route::post('/read/{id}', 'BookController@read');
      Route::post('/unread/{id}', 'BookController@unread');
      Route::post('/save', 'BookController@save');
    });
  });
});

Route::group(array('prefix' => 'templates'), function(){
  Route::get('/link-form', 'TemplateController@linkForm');
  Route::get('/book-form', 'TemplateController@bookForm');
});

Route::group(array('prefix' => 'migrations', 'before' => 'auth'), function(){
  Route::get('/books', 'MigrationsController@books');
  Route::get('/links', 'MigrationsController@links');
});

Route::filter('auth', function() {
  if (Auth::guest()) return App::abort(404);
});
