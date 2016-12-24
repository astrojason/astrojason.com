<?php

Route::get('', 'CreditController@query');
Route::post('', 'CreditController@save');
Route::put('', 'CreditController@add');
Route::get('report', 'CreditController@report');
Route::post('{id}', 'CreditController@save');
Route::delete('{id}', 'CreditController@disable');
