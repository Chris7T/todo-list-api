<?php

use Illuminate\Support\Facades\Route;

Route::prefix('project')->name('project.')->middleware(['api', 'auth:sanctum'])->group(function () {
    Route::apiResource('', 'ProjectController')->parameters(['' => 'project']);
    Route::get('unlink/{project}', 'ProjectController@unlink')->name('unlink');
    Route::get('link/{key}', 'ProjectController@link')->name('link');
    Route::get('generate/{project}', 'ProjectController@generateLink')->name('generate');
});
