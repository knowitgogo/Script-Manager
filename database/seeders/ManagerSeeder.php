<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $managers = [
            ['name' => 'Alice Manager', 'email' => 'alice.manager@example.com'],
            ['name' => 'Bob Supervisor', 'email' => 'bob.supervisor@example.com'],
            ['name' => 'Carol Lead', 'email' => 'carol.lead@example.com'],
            ['name' => 'David Executive', 'email' => 'david.executive@example.com'],
            ['name' => 'Emma Director', 'email' => 'emma.director@example.com'],
            ['name' => 'Fred Coordinator', 'email' => 'fred.coordinator@example.com'],
            ['name' => 'Greta Chief', 'email' => 'greta.chief@example.com'],
            ['name' => 'Hugo Partner', 'email' => 'hugo.partner@example.com'],
            ['name' => 'Iris Supervisor', 'email' => 'iris.supervisor@example.com'],
            ['name' => 'Jack Operator', 'email' => 'jack.operator@example.com'],
            ['name' => 'Kara Manager', 'email' => 'kara.manager@example.com'],
            ['name' => 'Liam Leader', 'email' => 'liam.leader@example.com'],
            ['name' => 'Maya Admin', 'email' => 'maya.admin@example.com'],
        ];

        foreach ($managers as $manager) {
            Manager::firstOrCreate(
                ['email' => $manager['email']],
                [
                    'name' => $manager['name'],
                    'password' => Hash::make('password123'),
                ]
            );
        }
    }
}
