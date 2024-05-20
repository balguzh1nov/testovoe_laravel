<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::get('notes', [NoteController::class, 'index']);
    Route::get('notes/{id}', [NoteController::class, 'show']);
    Route::post('notes', [NoteController::class, 'store']);
    Route::put('notes/{id}', [NoteController::class, 'update']);
    Route::delete('notes/{id}', [NoteController::class, 'destroy']);
});