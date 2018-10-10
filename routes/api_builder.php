<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes are defined.
|
*/

// get list of tasks
//Route::get('task','AppConnect@index');

// get specific task
Route::get('AppConnect/{id}','AppConnect@show');

// update existing task
//Route::put('AppConnect','AppConnect@store');

// create new task
//Route::post('AppConnect','AppConnect@store');



