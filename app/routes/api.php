<?php

Route::post('register', 'UserController@processRegistration');
Route::post('checkusername', 'UserController@checkUsername');
Route::post('checkemail', 'UserController@checkEmail');
Route::post('login', 'UserController@login');
Route::get('dashboard', 'DashboardController@get');

Route::group(array('before' => 'auth'), function() {
  Route::post('logout', 'UserController@logout');
  Route::group(array('prefix' => 'link'), function(){
    @include('api/link.php');
  });
  Route::group(array('prefix' => 'book'), function(){
    @include('api/book.php');
  });
  Route::group(array('prefix' => 'movie'), function(){
    @include('api/movie.php');
  });
  Route::group(array('prefix' => 'game'), function(){
    @include('api/game.php');
  });
  Route::group(array('prefix' => 'song'), function(){
    @include('api/song.php');
  });
});
