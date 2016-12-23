<?php
Route::get('account-form', 'TemplateController@accountForm');
Route::get('book-form', 'TemplateController@bookForm');
Route::get('link-form', 'TemplateController@linkForm');
Route::get('movie-form', 'TemplateController@movieForm');
Route::get('game-form', 'TemplateController@gameForm');
Route::get('song-form', 'TemplateController@songForm');

Route::get('loader', 'TemplateController@loader');
Route::get('paginator', 'TemplateController@paginator');

Route::get('account-modal', 'TemplateController@accountModal');
Route::get('book-modal', 'TemplateController@bookModal');
Route::get('game-modal', 'TemplateController@gameModal');
Route::get('link-modal', 'TemplateController@linkModal');
Route::get('movie-modal', 'TemplateController@movieModal');
Route::get('song-modal', 'TemplateController@songModal');
