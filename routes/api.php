<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TranslationController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware([\App\Http\Middleware\CheckApiToken::class])->group(function () {
    Route::get('/user',[AuthController::class, 'getUser']);
    Route::get('/export', [TranslationController::class, 'export']);
    Route::apiResource('translations', TranslationController::class);
});
