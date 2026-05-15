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
Route::prefix('topics')->name('topics.')->group(function () {
    Route::get('', [TopicController::class, 'index'])->name('index');
    Route::get('create', [TopicController::class, 'create'])->name('create');
    Route::post('', [TopicController::class, 'store'])->name('store');
    Route::get('{topic}', [TopicController::class, 'show'])->name('show');
    Route::get('{topic}/edit', [TopicController::class, 'edit'])->name('edit');
    Route::put('{topic}', [TopicController::class, 'update'])->name('update');
    Route::delete('{topic}', [TopicController::class, 'destroy'])->name('destroy');
    Route::put('{topic}/restore', [TopicController::class, 'restore'])->name('restore');
    Route::put('{topic}/forceDestroy', [TopicController::class, 'forceDestroy'])->name('forceDestroy');
    Route::get('trashed', [TopicController::class, 'trashed'])->name('trashed');
});

//QUESTION CONTROLLER
Route::prefix('questions')->name('questions.')->group(function () {
    Route::get('', [QuestionController::class, 'index'])->name('index');
    Route::get('create', [QuestionController::class, 'create'])->name('create');
    Route::post('', [QuestionController::class, 'store'])->name('store');
    Route::get('{question}', [QuestionController::class, 'show'])->name('show');
    Route::get('{question}/edit', [QuestionController::class, 'edit'])->name('edit');
    Route::put('{question}', [QuestionController::class, 'update'])->name('update');
    Route::delete('{question}', [QuestionController::class, 'destroy'])->name('destroy');
    Route::put('{question}/restore', [QuestionController::class, 'restore'])->name('restore');
    Route::delete('{question}/forceDestroy', [QuestionController::class, 'forceDestroy'])->name('forceDestroy');
    Route::get('trashed', [QuestionController::class, 'trashed'])->name('trashed');

});

//QUESTIONLEVEL CONTROLLER
Route::prefix('question_levels')->name('question_levels.')->group(function () {
    Route::get('', [QuestionLevelController::class, 'index'])->name('index');
});

//RESULT CONTROLLER
Route::prefix('results')->name('results.')->group(function () {
    Route::get('', [ResultController::class, 'index'])->name('index');
    Route::get('create', [ResultController::class, 'create'])->name('create');
    Route::get('', [ResultController::class, 'store'])->name('store');
    Route::get('{result}', [ResultController::class, 'show'])->name('show');
    Route::delete('{result}', [ResultController::class, 'destroy'])->name('destroy');
    Route::put('{result}/restore', [ResultController::class, 'restore'])->name('restore');
    Route::delete('{result}/forceDestroy', [ResultController::class, 'forceDestroy'])->name('forceDestroy');
    Route::get('trashed', [ResultController::class, 'trashed'])->name('trashed');
});

//TEST CONTROLLER
Route::prefix('tests')->name('tests.')->group(function () {
    Route::get('', [TestController::class, 'index'])->name('index');
    Route::get('create', [TestController::class, 'create'])->name('create');
    Route::post('', [TestController::class, 'store'])->name('store');
    Route::get('{test}', [TestController::class, 'show'])->name('show');
    Route::get('{test}/edit', [TestController::class, 'edit'])->name('edit');
    Route::put('{test}', [TestController::class, 'update'])->name('update');
    Route::delete('{test}', [TestController::class, 'destroy'])->name('destroy');
    Route::put('{test}/restore', [TestController::class, 'restore'])->name('restore');
    Route::delete('{test}/forceDestroy', [TestController::class, 'forceDestroy'])->name('forceDestroy');
    Route::get('trashed', [TestController::class, 'trashed'])->name('trashed');
});

//USERLOG CONTROLLER
Route::prefix('user_logs')->name('user_logs.')->group(function () {
    Route::get('', [UserLogController::class, 'index'])->name('index');
});

