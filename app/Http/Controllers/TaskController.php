<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        // Si es una petición AJAX/API, devolver JSON
        if (request()->expectsJson() || request()->is('api/*')) {
            // Datos de prueba para mostrar las tareas
            $sampleTasks = [
                [
                    'id' => 1,
                    'title' => 'Proyecto Final Base de Datos',
                    'description' => 'Implementar sistema completo con CRUD y consultas avanzadas',
                    'due_date' => '2025-09-30T23:59:00',
                    'priority' => 'high',
                    'status' => 'pending',
                    'subject' => ['name' => 'Base de Datos']
                ],
                [
                    'id' => 2,
                    'title' => 'Ensayo Algoritmos de Ordenamiento',
                    'description' => 'Análisis comparativo de quicksort vs mergesort',
                    'due_date' => '2025-09-28T18:00:00',
                    'priority' => 'medium',
                    'status' => 'in_progress',
                    'subject' => ['name' => 'Programación']
                ],
                [
                    'id' => 3,
                    'title' => 'Ejercicios Cálculo Integral',
                    'description' => 'Resolver ejercicios del capítulo 7',
                    'due_date' => '2025-09-26T12:00:00',
                    'priority' => 'low',
                    'status' => 'completed',
                    'subject' => ['name' => 'Matemáticas']
                ]
            ];

            return response()->json($sampleTasks);
        }

        // Si es una petición web normal, devolver la vista
        return view('task');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'entity_type' => 'task',
            'entity_id' => $task->id,
            'description' => "Nueva tarea creada: {$task->title}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente',
            'task' => $task->load('subject')
        ]);
    }

    public function update(Request $request, Task $task)
    {
        // Ensure user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|required|date',
            'priority' => 'sometimes|required|in:low,medium,high',
            'status' => 'sometimes|required|in:pending,in_progress,completed,overdue',
        ]);

        $oldStatus = $task->status;
        $task->update($request->all());

        // If task was completed, set completion date
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $task->update(['completion_date' => now()]);
        }

        // Log activity
        $action = $request->status === 'completed' ? 'completed' : 'updated';
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => 'task',
            'entity_id' => $task->id,
            'description' => "Tarea {$action}: {$task->title}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente',
            'task' => $task->load('subject')
        ]);
    }

    public function destroy(Task $task)
    {
        // Ensure user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $taskTitle = $task->title;
        $task->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'entity_type' => 'task',
            'entity_id' => $task->id,
            'description' => "Tarea eliminada: {$taskTitle}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }

    public function toggle($taskId)
    {
        // Para propósitos de demostración, simular el cambio de estado
        // En una aplicación real, esto actualizaría la base de datos
        
        return response()->json([
            'success' => true,
            'message' => 'Estado de tarea actualizado',
            'task' => [
                'id' => $taskId,
                'status' => request()->input('status', 'completed')
            ]
        ]);
    }
}
