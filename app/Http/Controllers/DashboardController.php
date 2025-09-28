<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Si no hay usuario autenticado, mostrar datos de ejemplo
        if (!Auth::check()) {
            $stats = [
                'totalTasks' => 6,
                'pendingTasks' => 3,
                'completedTasks' => 2,
                'averageGrade' => 87.3,
            ];
            $recentTasks = collect([
                (object) [
                    'id' => 1,
                    'title' => 'Ensayo de Literatura',
                    'description' => 'Análisis de "Cien años de soledad"',
                    'due_date' => now()->addDays(3),
                    'status' => 'pending',
                    'priority' => 'medium',
                    'is_overdue' => false,
                    'is_today' => false,
                    'subject' => (object) ['name' => 'Literatura', 'color' => '#3b82f6']
                ],
                (object) [
                    'id' => 2,
                    'title' => 'Proyecto de Programación',
                    'description' => 'Sistema web con Laravel',
                    'due_date' => now()->addDays(7),
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'is_overdue' => false,
                    'is_today' => false,
                    'subject' => (object) ['name' => 'Programación', 'color' => '#10b981']
                ],
                (object) [
                    'id' => 3,
                    'title' => 'Examen de Matemáticas',
                    'description' => 'Cálculo diferencial e integral',
                    'due_date' => now()->addDays(1),
                    'status' => 'pending',
                    'priority' => 'high',
                    'is_overdue' => false,
                    'is_today' => false,
                    'subject' => (object) ['name' => 'Matemáticas', 'color' => '#f59e0b']
                ],
                (object) [
                    'id' => 4,
                    'title' => 'Informe de Laboratorio',
                    'description' => 'Experimento de química orgánica',
                    'due_date' => now()->subDays(2),
                    'status' => 'pending',
                    'priority' => 'high',
                    'is_overdue' => true,
                    'is_today' => false,
                    'subject' => (object) ['name' => 'Química', 'color' => '#ef4444']
                ],
                (object) [
                    'id' => 5,
                    'title' => 'Presentación de Historia',
                    'description' => 'La Revolución Industrial',
                    'due_date' => now(),
                    'status' => 'in_progress',
                    'priority' => 'medium',
                    'is_overdue' => false,
                    'is_today' => true,
                    'subject' => (object) ['name' => 'Historia', 'color' => '#8b5cf6']
                ],
                (object) [
                    'id' => 6,
                    'title' => 'Tarea de Inglés',
                    'description' => 'Ejercicios de gramática',
                    'due_date' => now()->addDays(5),
                    'status' => 'completed',
                    'priority' => 'low',
                    'is_overdue' => false,
                    'is_today' => false,
                    'subject' => (object) ['name' => 'Inglés', 'color' => '#06b6d4']
                ],
            ]);
            $subjects = collect([
                (object) ['id' => 1, 'name' => 'Literatura'],
                (object) ['id' => 2, 'name' => 'Programación'],
                (object) ['id' => 3, 'name' => 'Matemáticas'],
                (object) ['id' => 4, 'name' => 'Historia'],
                (object) ['id' => 5, 'name' => 'Química'],
                (object) ['id' => 6, 'name' => 'Inglés'],
            ]);
            $recentActivity = collect([
                (object) [
                    'description' => 'Tarea completada: Ensayo de Historia',
                    'created_at' => now()->subHours(2),
                ],
                (object) [
                    'description' => 'Nueva calificación registrada: 92/100',
                    'created_at' => now()->subHours(5),
                ],
                (object) [
                    'description' => 'Tarea creada: Proyecto de Programación',
                    'created_at' => now()->subDay(),
                ],
            ]);
            
            // Weekly progress data for demo
            $weeklyProgress = [
                ['week' => 'Sem 1', 'completed' => 8, 'pending' => 3, 'overdue' => 1],
                ['week' => 'Sem 2', 'completed' => 12, 'pending' => 2, 'overdue' => 0],
                ['week' => 'Sem 3', 'completed' => 6, 'pending' => 5, 'overdue' => 2],
                ['week' => 'Sem 4', 'completed' => 10, 'pending' => 1, 'overdue' => 0],
                ['week' => 'Sem 5', 'completed' => 4, 'pending' => 8, 'overdue' => 1],
            ];
            
            // Calendar data for demo
            $calendarTasks = [
                now()->format('Y-m-d') => 2,
                now()->addDays(1)->format('Y-m-d') => 1,
                now()->addDays(3)->format('Y-m-d') => 3,
                now()->addDays(7)->format('Y-m-d') => 1,
            ];
            
            return view('dashboard', compact('stats', 'recentTasks', 'subjects', 'recentActivity', 'weeklyProgress', 'calendarTasks'));
        }

        // Original code for authenticated users
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'totalTasks' => $user->tasks()->count(),
            'pendingTasks' => $user->tasks()->pending()->count(),
            'completedTasks' => $user->tasks()->completed()->count(),
            'averageGrade' => $user->grades()->avg('grade') ?? 0,
        ];

        // Get recent tasks (ordered by due date)
        $recentTasks = $user->tasks()
            ->with('subject')
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        // Get subjects for dropdowns
        $subjects = Subject::orderBy('name')->get();

        // Get recent activity (last 10 activities)
        $recentActivity = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get weekly progress data (last 5 weeks)
        $weeklyProgress = [];
        for ($i = 4; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            
            $completed = $user->tasks()
                ->where('status', 'completed')
                ->whereBetween('due_date', [$weekStart, $weekEnd])
                ->count();
                
            $pending = $user->tasks()
                ->where('status', 'pending')
                ->whereBetween('due_date', [$weekStart, $weekEnd])
                ->count();
                
            $overdue = $user->tasks()
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->whereBetween('due_date', [$weekStart, $weekEnd])
                ->count();
            
            $weeklyProgress[] = [
                'week' => 'Sem ' . (5 - $i),
                'completed' => $completed,
                'pending' => $pending,
                'overdue' => $overdue,
            ];
        }

        // Get calendar data (tasks grouped by date)
        $calendarTasks = $user->tasks()
            ->selectRaw('DATE(due_date) as task_date, COUNT(*) as task_count')
            ->whereBetween('due_date', [now()->startOfMonth()->subMonth(), now()->endOfMonth()->addMonth()])
            ->groupBy('task_date')
            ->pluck('task_count', 'task_date')
            ->toArray();

        return view('dashboard', compact('stats', 'recentTasks', 'subjects', 'recentActivity', 'weeklyProgress', 'calendarTasks'));
    }

    public function getStats()
    {
        $user = Auth::user();
        
        return response()->json([
            'totalTasks' => $user->tasks()->count(),
            'pendingTasks' => $user->tasks()->pending()->count(),
            'completedTasks' => $user->tasks()->completed()->count(),
            'averageGrade' => round($user->grades()->avg('grade') ?? 0, 1),
        ]);
    }

    public function getUpcomingTasks()
    {
        $user = Auth::user();
        
        $upcomingTasks = $user->tasks()
            ->with('subject')
            ->upcoming(7)
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json($upcomingTasks);
    }
}
