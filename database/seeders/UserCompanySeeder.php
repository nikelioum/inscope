<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;

class UserCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $users = User::all();
        $companies = Company::all();

        
        foreach ($users as $user) {
            
            $assignedCompanies = $companies->random(rand(1, 3))->pluck('id');
            $user->companies()->sync($assignedCompanies);
        }
    }
}
