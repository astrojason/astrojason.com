<?php

Route::get('', 'MovieController@query');
Route::post('', 'MovieController@save');
Route::delete('', 'MovieController@delete');
Route::get('widget', 'MovieController@widget');