<?php

Route::get('dashboard', 'DashboardController@get');

Route::group(['prefix' => 'user'], function(){
  @include('api/user.php');
});

Route::group(['before' => 'auth'], function() {
  Route::group(['prefix' => 'book'], function(){
    @include('api/book.php');
  });
  Route::group(['prefix' => 'credit'], function(){
    @include('api/credit.php');
  });
  Route::group(['prefix' => 'game'], function(){
    @include('api/game.php');
  });
  Route::group(['prefix' => 'link'], function(){
    @include('api/link.php');
  });
  Route::group(['prefix' => 'movie'], function(){
    @include('api/movie.php');
  });
  Route::group(['prefix' => 'song'], function(){
    @include('api/song.php');
  });
});
