<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionLevelController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

//TOPIC CONTROLLER
Route::prefix('topics')->name('topics.')->group(function(){
    Route::get('', [TopicController::class, 'index'])->name('index');
    Route::get('create',[TopicController::class, 'create'])->name('create');
    Route::post('',[TopicController::class, 'store'])->name('store');
});

//QUESTION CONTROLLER
Route::prefix('questions')->name('questions.')->group(function(){
   Route::get('', [QuestionController::class, 'index'])->name('index');
});

//QUESTIONLEVEL CONTROLLER
Route::prefix('question_levels')->name('question_levels.')->group(function(){
    Route::get('', [QuestionLevelController::class, 'index'])->name('index');
});

//RESULT CONTROLLER
Route::prefix('results')->name('results.')->group(function(){
    Route::get('', [ResultController::class, 'index'])->name('index');
});

//TEST CONTROLLER
Route::prefix('tests')->name('tests.')->group(function(){
    Route::get('', [TestController::class, 'index'])->name('index');
});

//USERLOG CONTROLLER
Route::prefix('user_logs')->name('user_logs.')->group(function(){
    Route::get('', [UserLogController::class, 'index'])->name('index');
});

