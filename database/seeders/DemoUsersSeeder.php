<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'candidat@carreergo.test'],
            [
                'name' => 'Demo Candidat',
                'password' => 'password',
                'role' => 'candidat',
            ]
        );

        $company = Company::firstOrCreate(['name' => 'TechNova Demo']);

        User::updateOrCreate(
            ['email' => 'recruteur@carreergo.test'],
            [
                'name' => 'Demo Recruteur',
                'password' => 'password',
                'role' => 'recruteur',
                'company_id' => $company->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@carreergo.test'],
            [
                'name' => 'Demo Admin',
                'password' => 'password',
                'role' => 'admin',
            ]
        );
    }
}
