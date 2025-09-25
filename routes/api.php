<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



// Rutas API para tareas (sin autenticación, modo demo)
Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'apiIndex']);
Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'apiStore']);
Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'apiUpdate']);
Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'apiDestroy']);

// Ruta API para asignaturas
Route::get('/subjects', function() {
	return \App\Models\Subject::select('id', 'name')->get();
});
