<?php

Route::get('', 'MovieController@query');
Route::post('', 'MovieController@save');
Route::get('populate', 'MovieController@populate');
Route::get('widget', 'MovieController@widget');
Route::group(['prefix' => '{movieId}'], function(){
  Route::post('', 'MovieController@save');
  Route::delete('', 'MovieController@delete');
});