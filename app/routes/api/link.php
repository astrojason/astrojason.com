<?php

Route::get('', 'LinkController@query');
Route::post('', 'LinkController@save');
Route::get('populate', 'LinkController@populateLinks');
Route::post('import', 'LinkController@importLinks');
Route::get('readtoday', 'LinkController@readToday');
Route::group(['prefix' => '{linkId}'], function(){
  Route::delete('', 'LinkController@delete');
  Route::post('', 'LinkController@save');
});
