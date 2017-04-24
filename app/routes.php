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
Route::get('register', 'HomeController@register');

Route::group(['before' => 'auth'], function(){
  Route::group(['prefix' => 'account'], function(){
    Route::get('', 'UserController@account');
    Route::post('', 'UserController@update');
    Route::group(['prefix' => 'dashboard-category'], function(){
      Route::post('', 'DashboardCategoryController@add');
      Route::post('{dashboardCategoryId}', 'DashboardCategoryController@update');
    });
  });
  Route::get('games', 'GameController@index');
  Route::get('articles', 'LinkController@index');
  Route::get('readlater', 'LinkController@readLater');
  Route::get('songs', 'SongController@index');
  Route::get('charts', 'ChartController@index');
  Route::get('credit', 'CreditController@index');
});

Route::group(['prefix' => 'books'], function(){
  @include('routes/books.php');
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
  @include('routes/api.php');
});

Route::group(array('prefix' => 'templates'), function(){
  @include('routes/templates.php');
});

Route::group(['prefix' => 'modals'], function(){
  Route::get('article-form', 'ModalController@articleForm');
});

Route::group(['prefix' => 'react'], function(){
  Route::get('', 'HomeController@react');
});