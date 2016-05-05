<?php

Route::put('', 'UserController@processRegistration');
Route::post('login', 'UserController@login');
Route::post('checkusername', 'UserController@checkUsername');
Route::post('checkemail', 'UserController@checkEmail');
Route::group(['before' => 'auth'], function() {
  Route::post('logout', 'UserController@logout');
});