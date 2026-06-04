<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionLevelController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

//TOPIC CONTROLLER
Route::prefix('topics')->name('topics.')->group(function () {
    Route::get('', [TopicController::class, 'index'])
        ->name('index');
    Route::get('create', [TopicController::class, 'create'])
        ->middleware(['auth', 'role:admin'])
        ->name('create');
    Route::post('', [TopicController::class, 'store'])
        ->middleware(['auth', 'role:admin'])
        ->name('store');
    Route::get('trashed', [TopicController::class, 'trashed'])
        ->middleware(['auth', 'role:admin'])
        ->name('trashed');
    Route::get('{topic}', [TopicController::class, 'show'])
        ->name('show');
    Route::get('{topic}/edit', [TopicController::class, 'edit'])
        ->middleware(['auth', 'role:admin'])
        ->name('edit');
    Route::put('{topic}', [TopicController::class, 'update'])
        ->middleware(['auth', 'role:admin'])
        ->name('update');
    Route::delete('{topic}', [TopicController::class, 'destroy'])
        ->middleware(['auth', 'role:admin'])
        ->name('destroy');
    Route::put('{topic}/restore', [TopicController::class, 'restore'])
        ->middleware(['auth', 'role:admin'])
        ->name('restore');
    Route::delete('{topic}/forceDestroy', [TopicController::class, 'forceDestroy'])
        ->middleware(['auth', 'role:admin'])
        ->name('forceDestroy');
});

//QUESTION CONTROLLER
Route::prefix('questions')->name('questions.')->group(function () {
    Route::get('', [QuestionController::class, 'index'])
        ->name('index');
    Route::get('create', [QuestionController::class, 'create'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('create');
    Route::post('', [QuestionController::class, 'store'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('store');
    Route::get('trashed', [QuestionController::class, 'trashed'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('trashed');
    Route::get('{question}', [QuestionController::class, 'show'])
        ->name('show');
    Route::get('{question}/edit', [QuestionController::class, 'edit'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('edit');
    Route::put('{question}', [QuestionController::class, 'update'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('update');
    Route::delete('{question}', [QuestionController::class, 'destroy'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('destroy');
    Route::put('{question}/restore', [QuestionController::class, 'restore'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('restore');
    Route::delete('{question}/forceDestroy', [QuestionController::class, 'forceDestroy'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('forceDestroy');
});

//QUESTIONLEVEL CONTROLLER
Route::prefix('question_levels')->name('question_levels.')->group(function () {
    Route::get('', [QuestionLevelController::class, 'index'])
        ->name('index');
});

//RESULT CONTROLLER
Route::prefix('results')->name('results.')->group(function () {
    Route::get('', [ResultController::class, 'index'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('index');
    Route::get('create', [ResultController::class, 'create'])
        ->middleware(['auth', 'role:admin'])
        ->name('create');
    Route::post('', [ResultController::class, 'store'])
        ->middleware(['auth', 'role:admin'])
        ->name('store');
    Route::get('trashed', [ResultController::class, 'trashed'])
        ->middleware(['auth', 'role:admin'])
        ->name('trashed');
    Route::get('my', [ResultController::class, 'myResults'])
        ->middleware(['auth'])
        ->name('my');
    Route::get('{result}', [ResultController::class, 'show'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('show');
    Route::delete('{result}', [ResultController::class, 'destroy'])
        ->middleware(['auth', 'role:admin'])
        ->name('destroy');
    Route::put('{result}/restore', [ResultController::class, 'restore'])
        ->middleware(['auth', 'role:admin'])
        ->name('restore');
    Route::delete('{result}/forceDestroy', [ResultController::class, 'forceDestroy'])
        ->middleware(['auth', 'role:admin'])
        ->name('forceDestroy');
});

//TEST CONTROLLER
Route::prefix('tests')->name('tests.')->group(function () {
    Route::get('', [TestController::class, 'index'])
        ->name('index');
    Route::get('create', [TestController::class, 'create'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('create');
    Route::post('', [TestController::class, 'store'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('store');
    Route::get('trashed', [TestController::class, 'trashed'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('trashed');
    Route::get('{test}', [TestController::class, 'show'])
        ->name('show');
    Route::get('{test}/edit', [TestController::class, 'edit'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('edit');
    Route::put('{test}', [TestController::class, 'update'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('update');
    Route::delete('{test}', [TestController::class, 'destroy'])
        ->middleware(['auth', 'role:admin'])
        ->name('destroy');
    Route::put('{test}/restore', [TestController::class, 'restore'])
        ->middleware(['auth', 'role:admin,teacher'])
        ->name('restore');
    Route::delete('{test}/forceDestroy', [TestController::class, 'forceDestroy'])
        ->middleware(['auth', 'role:admin'])
        ->name('forceDestroy');
});

//USERLOG CONTROLLER
Route::prefix('user_logs')->name('user_logs.')->group(function () {
    Route::get('', [UserLogController::class, 'index'])
        ->middleware(['auth', 'role:admin'])
        ->name('index');
    Route::get('{user_log', [UserLogController::class, 'show'])
        ->middleware(['auth', 'role:admin'])
        ->name('show');
});


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::put('/admin/users/{user}/toggle-active', [UserController::class, 'toggleActive'])
    ->name('admin.users.toggleActive');

