<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        // Si es una petición AJAX/API, devolver JSON
        if (request()->expectsJson() || request()->is('api/*')) {
            // Datos de prueba para mostrar las notas
            $sampleGrades = [
                [
                    'id' => 1,
                    'evaluation_type' => 'Parcial 1',
                    'grade' => 6.5,
                    'weight' => 0.3,
                    'evaluation_date' => '2025-09-15',
                    'subject' => ['name' => 'Base de Datos']
                ],
                [
                    'id' => 2,
                    'evaluation_type' => 'Laboratorio',
                    'grade' => 7.0,
                    'weight' => 0.2,
                    'evaluation_date' => '2025-09-20',
                    'subject' => ['name' => 'Programación']
                ]
            ];

            return response()->json($sampleGrades);
        }

        // Si es una petición web normal, devolver la vista
        return view('grades');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'evaluation_type' => 'required|string|max:100',
            'grade' => 'required|numeric|min:1|max:7',
            'weight' => 'required|numeric|min:0|max:1',
            'evaluation_date' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        $grade = Auth::user()->grades()->create($request->all());

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'entity_type' => 'grade',
            'entity_id' => $grade->id,
            'description' => "Nueva nota registrada: {$grade->subject->name} - {$grade->grade}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nota guardada exitosamente',
            'grade' => $grade->load('subject')
        ]);
    }

    public function calculateFinalGrade(Request $request)
    {
        $request->validate([
            'grade1' => 'required|numeric|min:1|max:7',
            'grade2' => 'required|numeric|min:1|max:7',
            'examGrade' => 'required|numeric|min:1|max:7',
        ]);

        $finalGrade = ($request->grade1 * 0.3) + ($request->grade2 * 0.3) + ($request->examGrade * 0.4);

        return response()->json([
            'finalGrade' => round($finalGrade, 2)
        ]);
    }

    public function saveCalculatedGrade(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade1' => 'required|numeric|min:1|max:7',
            'grade2' => 'required|numeric|min:1|max:7',
            'examGrade' => 'required|numeric|min:1|max:7',
            'finalGrade' => 'required|numeric|min:1|max:7',
        ]);

        $user = Auth::user();
        $subject = Subject::find($request->subject_id);

        // Save individual grades
        $grades = [
            ['evaluation_type' => 'Nota 1', 'grade' => $request->grade1, 'weight' => 0.30],
            ['evaluation_type' => 'Nota 2', 'grade' => $request->grade2, 'weight' => 0.30],
            ['evaluation_type' => 'Examen', 'grade' => $request->examGrade, 'weight' => 0.40],
        ];

        foreach ($grades as $gradeData) {
            $user->grades()->create([
                'subject_id' => $request->subject_id,
                'evaluation_type' => $gradeData['evaluation_type'],
                'grade' => $gradeData['grade'],
                'weight' => $gradeData['weight'],
                'evaluation_date' => now()->toDateString(),
            ]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'calculated',
            'entity_type' => 'grade',
            'entity_id' => 0,
            'description' => "Notas calculadas para {$subject->name} - Promedio: {$request->finalGrade}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notas guardadas exitosamente'
        ]);
    }

    public function getSubjectAverage($subjectId)
    {
        $user = Auth::user();
        
        $grades = $user->grades()
            ->where('subject_id', $subjectId)
            ->get();

        if ($grades->isEmpty()) {
            return response()->json(['average' => 0]);
        }

        $totalWeight = $grades->sum('weight');
        $weightedSum = $grades->sum(function ($grade) {
            return $grade->grade * $grade->weight;
        });

        $average = $totalWeight > 0 ? $weightedSum / $totalWeight : 0;

        return response()->json(['average' => round($average, 2)]);
    }
}
