<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

// Registro
Route::get('/register', function() {
    return view('register');
})->name('register');

Route::post('/register', function(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    Auth::login($user);
    return redirect('/');
});

// Login
Route::get('/login', function() {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'webLogin']);

// Logout
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Dashboard simple para debugging
Route::get('/test', function() {
    return view('dashboard-simple');
});
Route::resource('tasks', TaskController::class);
Route::resource('grades', GradeController::class);
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

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
    Route::get('/subjects', function() {
        return \App\Models\Subject::select('id', 'name')->get();
    });
});

// Ruta temporal de debug
Route::get('/debug', function() {
    $users = User::with('tasks')->get();
    
    echo "<h2>Debug de Usuarios y Tareas</h2>";
    
    foreach($users as $user) {
        echo "<p>Usuario: {$user->email} (ID: {$user->id}) - Tareas: {$user->tasks->count()}</p>";
        
        foreach($user->tasks as $task) {
            echo "<li>{$task->title} - Subject ID: {$task->subject_id} - Status: {$task->status}</li>";
        }
    }
    
    echo "<hr><h3>Todas las tareas:</h3>";
    $allTasks = Task::with('subject', 'user')->get();
    foreach($allTasks as $task) {
        echo "<p>Tarea: {$task->title} - Usuario: {$task->user->email} - Materia: " . ($task->subject ? $task->subject->name : 'Sin materia') . "</p>";
    }
});

// Debug endpoint para tareas sin autenticación
Route::get('/debug-tasks', function() {
    $tasks = Task::with('subject', 'user')->get();
    return response()->json($tasks);
});

// Test de carga de tareas para usuario específico
Route::get('/test-tasks/{userId}', function($userId) {
    $user = User::findOrFail($userId);
    $tasks = Task::where('user_id', $userId)
                 ->with('subject')
                 ->orderBy('due_date', 'asc')
                 ->get();
                 
    return response()->json([
        'user' => $user->email,
        'tasks_count' => $tasks->count(),
        'tasks' => $tasks
    ]);
});
