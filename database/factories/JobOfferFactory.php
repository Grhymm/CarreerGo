<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobOfferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory()->recruteur(),
            'title' => fake()->jobTitle(),
            'location' => fake()->city(),
            'description' => fake()->paragraph(),
            'contract_type' => fake()->randomElement(['CDI', 'CDD', 'Freelance', 'Stage']),
            'salary_min' => null,
            'salary_max' => null,
            'show_salary' => false,
            'stage_duration_months' => null,
            'stage_stipend' => null,
            'guaranteed_response' => false,
            'status' => 'published',
        ];
    }
}
