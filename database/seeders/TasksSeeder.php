<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Subject;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = Subject::all();
        
        if ($subjects->isEmpty()) {
            return; // No hay asignaturas, no crear tareas
        }

        $tasks = [
            [
                'title' => 'Ensayo de Literatura',
                'description' => 'Análisis de "Cien años de soledad"',
                'due_date' => now()->addDays(3),
                'priority' => 'medium',
                'status' => 'pending',
                'subject_id' => $subjects->where('name', 'Literatura')->first()->id ?? $subjects->first()->id,
            ],
            [
                'title' => 'Proyecto de Programación',
                'description' => 'Sistema web con Laravel',
                'due_date' => now()->addDays(7),
                'priority' => 'high',
                'status' => 'in_progress',
                'subject_id' => $subjects->where('name', 'Programación')->first()->id ?? $subjects->first()->id,
            ],
            [
                'title' => 'Examen de Matemáticas',
                'description' => 'Cálculo diferencial e integral',
                'due_date' => now()->addDays(1),
                'priority' => 'high',
                'status' => 'pending',
                'subject_id' => $subjects->where('name', 'Matemáticas')->first()->id ?? $subjects->first()->id,
            ],
            [
                'title' => 'Informe de Laboratorio',
                'description' => 'Experimento de química orgánica',
                'due_date' => now()->subDays(2),
                'priority' => 'high',
                'status' => 'overdue',
                'subject_id' => $subjects->where('name', 'Química')->first()->id ?? $subjects->first()->id,
            ],
            [
                'title' => 'Presentación de Historia',
                'description' => 'La Revolución Industrial',
                'due_date' => now(),
                'priority' => 'medium',
                'status' => 'in_progress',
                'subject_id' => $subjects->where('name', 'Historia')->first()->id ?? $subjects->first()->id,
            ],
            [
                'title' => 'Tarea de Inglés',
                'description' => 'Ejercicios de gramática',
                'due_date' => now()->addDays(5),
                'priority' => 'low',
                'status' => 'completed',
                'subject_id' => $subjects->where('name', 'Inglés')->first()->id ?? $subjects->first()->id,
            ],
        ];

        // Crear tareas para usuarios específicos
        $user1 = \App\Models\User::where('email', 'juan@studyflow.com')->first();
        $user2 = \App\Models\User::where('email', 'maria@studyflow.com')->first();
        
        foreach ($tasks as $index => $task) {
            // Asignar tareas alternando usuarios
            $task['user_id'] = ($index % 2 == 0) ? $user1->id : $user2->id;
            Task::create($task);
        }
    }
}
