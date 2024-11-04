<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        Company::create(['name' => 'Tech Solutions', 'address' => '123 Tech Lane']);
        Company::create(['name' => 'Health Corp', 'address' => '456 Wellness St']);
        Company::create(['name' => 'EduSmart', 'address' => '789 Education Blvd']);
    }
}
