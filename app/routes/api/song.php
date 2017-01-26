<?php

Route::get('', 'SongController@query');
Route::post('', 'SongController@save');
Route::get('populate', 'SongController@populate');
Route::group(['prefix' => '{songId}'], function(){
  Route::post('', 'SongController@save');
  Route::delete('', 'SongController@delete');
});
