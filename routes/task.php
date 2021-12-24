<?php

use Illuminate\Support\Facades\Route;

Route::prefix('task')->name('task.')->middleware(['api', 'auth:sanctum'])->group(function () {
    Route::apiResource('', 'TaskController')->except('index')->parameters(['' => 'task']);
    Route::post('list', 'TaskController@list')->name('list');
});
