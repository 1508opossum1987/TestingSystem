<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

//TOPIC CONTROLLER
Route::prefix('topics')->name('topics.')->group(function(){
    Route::get('', [TopicController::class, 'index'])->name('index');
});



