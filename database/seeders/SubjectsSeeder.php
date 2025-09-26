<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['name' => 'Literatura'],
            ['name' => 'Programación'],
            ['name' => 'Matemáticas'],
            ['name' => 'Historia'],
            ['name' => 'Química'],
            ['name' => 'Inglés'],
            ['name' => 'Física'],
            ['name' => 'Biología'],
            ['name' => 'Filosofía'],
            ['name' => 'Arte'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
