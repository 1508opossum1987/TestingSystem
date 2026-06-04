<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestApiController;
use App\Http\Controllers\Api\ResultApiController;
use App\Http\Controllers\Api\TopicApiController;
use App\Http\Controllers\Api\QuestionApiController;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ONLY READ WITHOUT AUTH
Route::get('/tests', [TestApiController::class, 'index']);
Route::get('/tests/{test}', [TestApiController::class, 'show']);
Route::get('/topics', [TopicApiController::class, 'index']);
Route::get('/topics/{topic}', [TopicApiController::class, 'show']);
Route::get('/questions', [QuestionApiController::class, 'index']);
Route::get('/questions/{question}', [QuestionApiController::class, 'show']);

// ROUTES WITH MIDDLEWARE
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/tests/{test}/start', [TestApiController::class, 'start']);
    Route::post('/tests/{test}/submit', [TestApiController::class, 'submit']);

    Route::get('/results/my', [ResultApiController::class, 'myResults']);

    Route::middleware(['role:admin,teacher'])->group(function () {
        Route::apiResource('questions', QuestionApiController::class)->except(['index', 'show']);
        Route::get('/results', [ResultApiController::class, 'index']);
        Route::get('/results/{result}', [ResultApiController::class, 'show']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('topics', TopicApiController::class)->except(['index', 'show']);
        Route::get('/users', [AuthController::class, 'users']);
        Route::put('/users/{user}/role', [AuthController::class, 'updateRole']);
        Route::put('/users/{user}/toggle-active', [AuthController::class, 'toggleActive']);
        Route::delete('/results/{result}', [ResultApiController::class, 'destroy']);
        Route::delete('/tests/{test}', [TestApiController::class, 'destroy']);
    });
});
