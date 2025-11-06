<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '9876543210', 'role' => 'customer', 'password' => 'password123'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '9123456780', 'role' => 'employee', 'password' => 'password123'],
            ['name' => 'Agent Alex', 'email' => 'alex.agent@example.com', 'phone' => '9988776655', 'role' => 'agent', 'password' => 'password123'],
            ['name' => 'Admin Andy', 'email' => 'admin@example.com', 'phone' => '9112233445', 'role' => 'admin', 'password' => 'password123'],
            ['name' => 'Customer Chris', 'email' => 'chris.customer@example.com', 'phone' => '9001122334', 'role' => 'customer', 'password' => 'password123'],
        ];

        foreach ($users as $user) {
            $user['password'] = bcrypt($user['password']);
            User::create($user);
        }
    }
}
