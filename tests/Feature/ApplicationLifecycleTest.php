<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_candidate_can_apply_to_a_published_offer(): void
    {
        $jobOffer = JobOffer::factory()->create(['status' => 'published']);
        $candidat = User::factory()->candidat()->create();

        $response = $this->actingAs($candidat)->post("/jobs/{$jobOffer->id}/applications");

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'user_id' => $candidat->id,
            'job_offer_id' => $jobOffer->id,
            'status' => 'new',
        ]);
    }

    public function test_a_candidate_cannot_apply_twice_to_the_same_offer(): void
    {
        $jobOffer = JobOffer::factory()->create(['status' => 'published']);
        $candidat = User::factory()->candidat()->create();

        $this->actingAs($candidat)->post("/jobs/{$jobOffer->id}/applications");
        $response = $this->actingAs($candidat)->post("/jobs/{$jobOffer->id}/applications");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('applications', 1);
    }

    public function test_the_owning_recruiter_can_update_the_application_status(): void
    {
        $recruiter = User::factory()->recruteur()->create(['company_id' => Company::factory()]);
        $jobOffer = JobOffer::factory()->create(['status' => 'published', 'user_id' => $recruiter->id]);
        $candidat = User::factory()->candidat()->create();

        $application = Application::create([
            'user_id' => $candidat->id,
            'job_offer_id' => $jobOffer->id,
            'status' => 'new',
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($recruiter)
            ->patch("/recruiter/applications/{$application->id}", ['status' => 'interview']);

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'interview',
        ]);
    }

    public function test_a_non_owning_recruiter_cannot_update_the_application_status(): void
    {
        $owner = User::factory()->recruteur()->create(['company_id' => Company::factory()]);
        $otherRecruiter = User::factory()->recruteur()->create(['company_id' => Company::factory()]);
        $jobOffer = JobOffer::factory()->create(['status' => 'published', 'user_id' => $owner->id]);
        $candidat = User::factory()->candidat()->create();

        $application = Application::create([
            'user_id' => $candidat->id,
            'job_offer_id' => $jobOffer->id,
            'status' => 'new',
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($otherRecruiter)
            ->patch("/recruiter/applications/{$application->id}", ['status' => 'interview']);

        $response->assertForbidden();
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'new',
        ]);
    }
}
