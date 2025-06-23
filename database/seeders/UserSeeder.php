<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'Status' => 1,
            'CompanyCode' => 'SPORT001',
            'isDeleted' => 0,
            'CreatedBy' => 'system',
            'CreatedDate' => now(),
            'LastUpdatedBy' => 'system',
            'LastUpdatedDate' => now(),
        ]);
    }
}