<?php

Route::get('', 'GameController@query');
Route::post('', 'GameController@save');
Route::get('recommendation', 'GameController@recommend');
Route::group(['prefix' => '{gameId}'], function(){
  Route::post('', 'GameController@save');
  Route::delete('', 'GameController@delete');
});
