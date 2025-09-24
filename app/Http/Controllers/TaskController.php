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
        $user = Auth::user();
        $tasks = $user->tasks()
            ->with('subject')
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json($tasks);
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

    public function toggle(Task $task)
    {
        // Ensure user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
        $task->update([
            'status' => $newStatus,
            'completion_date' => $newStatus === 'completed' ? now() : null,
        ]);

        // Log activity
        $action = $newStatus === 'completed' ? 'completed' : 'reopened';
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => 'task',
            'entity_id' => $task->id,
            'description' => "Tarea {$action}: {$task->title}",
        ]);

        return response()->json([
            'success' => true,
            'message' => $newStatus === 'completed' ? 'Tarea completada' : 'Tarea marcada como pendiente',
            'task' => $task->load('subject')
        ]);
    }
}
