<?php

Route::get('/', 'GameController@query');
Route::post('/', 'GameController@save');
Route::delete('/', 'GameController@delete');
Route::get('recommendation', 'GameController@recommend');
