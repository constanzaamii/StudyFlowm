<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuario de prueba
        User::create([
            'student_id' => 'EST001',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'name' => 'Juan Pérez',
            'email' => 'juan@studyflow.com',
            'password' => Hash::make('password123'),
            'career' => 'Ingeniería en Sistemas',
            'year_level' => 3,
        ]);

        // Usuario demo
        User::create([
            'student_id' => 'EST002',
            'first_name' => 'María',
            'last_name' => 'González',
            'name' => 'María González',
            'email' => 'maria@studyflow.com',
            'password' => Hash::make('password123'),
            'career' => 'Administración',
            'year_level' => 2,
        ]);
    }
}
