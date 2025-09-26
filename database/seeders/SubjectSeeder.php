<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['name' => 'Programación'],
            ['name' => 'Base de Datos'],
            ['name' => 'Matemáticas'],
            ['name' => 'Inglés'],
            ['name' => 'Física'],
            ['name' => 'Química'],
            ['name' => 'Historia'],
            ['name' => 'Literatura'],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::firstOrCreate($subject);
        }
    }
}
