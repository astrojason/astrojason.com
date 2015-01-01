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

Route::get('/readlater', 'LinksController@readLater');

Route::group(array('prefix' => 'api'), function() {
  Route::post('/register', 'UserController@processRegistration');
  Route::post('/checkusername', 'UserController@checkUsername');
  Route::post('/checkemail', 'UserController@checkEmail');
  Route::post('/login', 'UserController@login');
  Route::group(array('before' => 'auth'), function() {
    Route::post('/logout', 'UserController@logout');
    Route::group(array('prefix' => 'links'), function(){
      Route::post('/save', 'LinksController@save');
      Route::post('/search', 'LinksController@search');
      Route::post('/open/{id}', 'LinksController@open');
      Route::post('/read/{id}', 'LinksController@read');
      Route::post('/unread/{id}', 'LinksController@unread');
      Route::post('/delete/{id}', 'LinksController@delete');
      Route::get('/dashboard', 'LinksController@getDashboard');
      Route::get('/dashboard/{category}', 'LinksController@getRandomLinks');
    });
  });
});

Route::group(array('prefix' => 'templates'), function(){
  Route::get('/link-form', 'TemplateController@linkForm');
  Route::get('/book-form', 'TemplateController@bookForm');
});

Route::filter('auth', function() {
  if (Auth::guest()) return App::abort(404);
});
