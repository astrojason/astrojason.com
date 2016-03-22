<?php

Route::get('', 'SongController@query');
Route::post('', 'SongController@save');
Route::group(['prefix' => '{songId}'], function(){
  Route::post('', 'SongController@save');
  Route::delete('', 'SongController@delete');
});
