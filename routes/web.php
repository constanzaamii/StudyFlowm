<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GradeController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('tasks', TaskController::class);
Route::resource('grades', GradeController::class);

// API Routes for AJAX
Route::prefix('api')->group(function () {
    Route::get('/tasks', [TaskController::class, 'apiIndex']);
    Route::post('/tasks', [TaskController::class, 'apiStore']);
    Route::put('/tasks/{task}', [TaskController::class, 'apiUpdate']);
    Route::delete('/tasks/{task}', [TaskController::class, 'apiDestroy']);
    
    Route::get('/grades', [GradeController::class, 'apiIndex']);
    Route::post('/grades', [GradeController::class, 'apiStore']);
    Route::put('/grades/{grade}', [GradeController::class, 'apiUpdate']);
    Route::delete('/grades/{grade}', [GradeController::class, 'apiDestroy']);
});
