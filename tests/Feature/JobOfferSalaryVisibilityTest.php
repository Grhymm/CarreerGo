<?php

namespace Tests\Feature;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class JobOfferSalaryVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_hidden_salary_is_not_sent_to_candidates(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'salary_min' => 300000,
            'salary_max' => 450000,
            'show_salary' => false,
        ]);

        $candidat = User::factory()->candidat()->create();
        $candidat->applications()->create([
            'job_offer_id' => $jobOffer->id,
            'status' => 'new',
            'applied_at' => now(),
        ]);

        $this->actingAs($candidat)
            ->get('/jobs')
            ->assertInertia(fn (Assert $page) => $page
                ->missing('jobOffers.0.salary_min')
                ->missing('jobOffers.0.salary_max')
            );

        $this->actingAs($candidat)
            ->get("/jobs/{$jobOffer->id}")
            ->assertInertia(fn (Assert $page) => $page
                ->missing('jobOffer.salary_min')
                ->missing('jobOffer.salary_max')
            );

        $this->actingAs($candidat)
            ->get('/applications')
            ->assertInertia(fn (Assert $page) => $page
                ->missing('applications.0.job_offer.salary_min')
                ->missing('applications.0.job_offer.salary_max')
            );
    }

    public function test_shown_salary_is_sent_to_candidates(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'salary_min' => 300000,
            'salary_max' => 450000,
            'show_salary' => true,
        ]);

        $candidat = User::factory()->candidat()->create();

        $this->actingAs($candidat)
            ->get("/jobs/{$jobOffer->id}")
            ->assertInertia(fn (Assert $page) => $page
                ->where('jobOffer.salary_min', 300000)
                ->where('jobOffer.salary_max', 450000)
            );
    }

    public function test_recruiter_still_sees_hidden_salary_when_editing(): void
    {
        $jobOffer = JobOffer::factory()->create([
            'salary_min' => 300000,
            'salary_max' => 450000,
            'show_salary' => false,
        ]);

        $this->actingAs($jobOffer->user)
            ->get("/job-offers/{$jobOffer->id}/edit")
            ->assertInertia(fn (Assert $page) => $page
                ->where('jobOffer.salary_min', 300000)
                ->where('jobOffer.salary_max', 450000)
            );
    }
}
