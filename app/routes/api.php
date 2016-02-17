<?php

Route::get('dashboard', 'DashboardController@get');

Route::group(['prefix' => 'user'], function(){
  Route::put('', 'UserController@processRegistration');
  Route::post('login', 'UserController@login');
  Route::post('checkusername', 'UserController@checkUsername');
  Route::post('checkemail', 'UserController@checkEmail');
  Route::group(['before' => 'auth'], function() {
    Route::post('logout', 'UserController@logout');
  });
});

Route::group(['before' => 'auth'], function() {
  Route::group(['prefix' => 'link'], function(){
    @include('api/link.php');
  });
  Route::group(['prefix' => 'book'], function(){
    @include('api/book.php');
  });
  Route::group(['prefix' => 'movie'], function(){
    @include('api/movie.php');
  });
  Route::group(['prefix' => 'game'], function(){
    @include('api/game.php');
  });
  Route::group(['prefix' => 'song'], function(){
    @include('api/song.php');
  });
});
