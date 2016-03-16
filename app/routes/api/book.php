<?php

Route::get('', 'BookController@query');
Route::post('', 'BookController@save');
Route::get('recommendation/{category}', 'BookController@recommendation');
Route::get('goodreads', 'BookController@goodreads');
Route::group(['prefix' => '{bookId}'], function(){
  Route::post('', 'BookController@save');
  Route::delete('', 'BookController@save');
});
