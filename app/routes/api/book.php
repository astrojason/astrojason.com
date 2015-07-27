<?php

Route::get('', 'BookController@query');
Route::post('', 'BookController@save');
Route::delete('', 'BookController@delete');
Route::get('recommendation/{category}', 'BookController@recommendation');
Route::get('goodreads', 'BookController@goodreads');
