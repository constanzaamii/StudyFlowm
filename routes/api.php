<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas de autenticación (públicas)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



// Ruta API para asignaturas (pública)
Route::get('/subjects', function() {
	return \App\Models\Subject::select('id', 'name')->get();
});

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Rutas API para tareas
    Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'apiIndex']);
    Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'apiStore']);
    Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'apiUpdate']);
    Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'apiDestroy']);
});
