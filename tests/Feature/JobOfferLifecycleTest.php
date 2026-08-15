<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class JobOfferLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_offer_goes_through_creation_moderation_and_becomes_visible_to_candidates(): void
    {
        $recruiter = User::factory()->recruteur()->create([
            'company_id' => Company::factory(),
        ]);

        $response = $this->actingAs($recruiter)->post('/job-offers', [
            'title' => 'Développeur Laravel',
            'description' => 'Une offre passionnante.',
            'location' => 'Paris',
            'contract_type' => 'CDI',
            'salary_min' => 35000,
            'salary_max' => 45000,
            'show_salary' => true,
            'guaranteed_response' => false,
        ]);

        $response->assertRedirect(route('job-offers.index'));

        $jobOffer = JobOffer::where('title', 'Développeur Laravel')->firstOrFail();
        $this->assertSame('pending', $jobOffer->status);

        $candidat = User::factory()->candidat()->create();

        $this->actingAs($candidat)
            ->get('/jobs')
            ->assertInertia(fn (Assert $page) => $page->has('jobOffers', 0));

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch("/admin/moderation/{$jobOffer->id}", ['status' => 'published'])
            ->assertRedirect(route('admin.moderation.index'));

        $this->assertSame('published', $jobOffer->fresh()->status);

        $this->actingAs($candidat)
            ->get('/jobs')
            ->assertInertia(fn (Assert $page) => $page
                ->has('jobOffers', 1)
                ->where('jobOffers.0.id', $jobOffer->id)
            );
    }
}
