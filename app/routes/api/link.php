<?php

Route::get('', 'LinkController@query');
Route::post('', 'LinkController@save');
Route::delete('', 'LinkController@delete');
Route::get('populate', 'LinkController@populateLinks');
Route::post('import', 'LinkController@importLinks');
