<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    \App\Models\Employee::create([
        'name' => 'Ferdi',
        'email' => 'ferdi@gmail.com',
        'password' => bcrypt('Armyofgod77'),
        'role' => 'staff',
        'join_date' => now(),
    ]);
}

    
}
