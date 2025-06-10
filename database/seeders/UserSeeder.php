<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin
        User::create([
            'name' => 'Admin Lisa',
            'email' => 'adminlisa@company.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'phone' => '081234567890',
            'address' => 'Jl. Rusa No. 11, Surabaya',
            'department' => 'Human Resources',
        ]);

        // Create default supervisor
        User::create([
            'name' => 'Supervisor Kevin',
            'email' => 'supervisorkevin@company.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'employee_id' => 'SPV001',
            'phone' => '081234567891',
            'address' => 'Jl. Mawar No. 321, Bandung',
            'department' => 'Human Resources',
        ]);

        // Create default customer
        User::create([
            'name' => 'Agung Budi Sentosa',
            'email' => 'customeragung@company.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567892',
            'address' => 'Jl. Air No. 123, Jakarta',
            'department' => 'Produksi',
        ]);
    }
}