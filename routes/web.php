<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GradeController;
use App\Models\User;
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

Route::post('/login', function(Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }
    return back()->with('error', 'Credenciales incorrectas');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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
