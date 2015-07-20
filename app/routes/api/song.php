<?php

Route::get('', 'SongController@query');
Route::post('', 'SongController@save');
Route::delete('', 'SongController@delete');
