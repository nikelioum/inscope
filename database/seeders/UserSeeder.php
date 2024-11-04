<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        $company1 = Company::first();
        $company2 = Company::skip(1)->first();

        // Admin User
        User::create([
            'name' => 'Admin User',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        // Standard Users
        User::create([
            'name' => 'John Doe',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'lastname' => 'Smith',
            'email' => 'janesmith@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        User::create([
            'name' => 'Alice Johnson',
            'lastname' => 'Johnson',
            'email' => 'alicejohnson@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);
    }
}
