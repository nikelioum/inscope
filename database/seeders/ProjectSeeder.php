<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Company;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $company1 = Company::first(); // Assuming company exists from CompanySeeder

        Project::create([
            'name' => 'Website Redesign',
            'description' => 'Standard project for redesigning the website',
            'company_id' => $company1->id,
            'type' => 'standard'
        ]);

        Project::create([
            'name' => 'New Product Launch',
            'description' => 'Complex project involving new product release and marketing',
            'company_id' => $company1->id,
            'type' => 'complex',
            'budget' => 50000,
            'timeline' => now()->addMonths(6)
        ]);

        // Additional projects can be added here similarly
    }
}
