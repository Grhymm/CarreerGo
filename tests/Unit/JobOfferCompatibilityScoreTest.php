<?php

namespace Tests\Unit;

use App\Models\JobOffer;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobOfferCompatibilityScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_zero_when_no_skills_are_shared(): void
    {
        $jobOffer = JobOffer::factory()->create();
        $jobOffer->skills()->attach(Skill::factory()->count(3)->create());

        $user = User::factory()->candidat()->create();
        $user->skills()->attach(Skill::factory()->count(2)->create());

        $this->assertSame(0, $jobOffer->compatibilityScoreFor($user));
    }

    public function test_it_returns_fifty_percent_for_a_partial_overlap(): void
    {
        $sharedSkills = Skill::factory()->count(2)->create();
        $offerOnlySkills = Skill::factory()->count(2)->create();

        $jobOffer = JobOffer::factory()->create();
        $jobOffer->skills()->attach($sharedSkills->merge($offerOnlySkills));

        $user = User::factory()->candidat()->create();
        $user->skills()->attach($sharedSkills);

        $this->assertSame(50, $jobOffer->compatibilityScoreFor($user));
    }

    public function test_it_returns_one_hundred_percent_when_all_required_skills_match(): void
    {
        $sharedSkills = Skill::factory()->count(3)->create();

        $jobOffer = JobOffer::factory()->create();
        $jobOffer->skills()->attach($sharedSkills);

        $user = User::factory()->candidat()->create();
        $user->skills()->attach($sharedSkills->merge(Skill::factory()->count(2)->create()));

        $this->assertSame(100, $jobOffer->compatibilityScoreFor($user));
    }

    public function test_it_returns_zero_without_dividing_by_zero_when_offer_has_no_required_skills(): void
    {
        $jobOffer = JobOffer::factory()->create();

        $user = User::factory()->candidat()->create();
        $user->skills()->attach(Skill::factory()->count(2)->create());

        $this->assertSame(0, $jobOffer->compatibilityScoreFor($user));
    }
}
