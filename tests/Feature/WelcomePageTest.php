<?php

namespace Tests\Feature;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_counts_only_published_offers(): void
    {
        JobOffer::factory()->count(2)->create(['status' => 'published']);
        JobOffer::factory()->create(['status' => 'pending']);
        JobOffer::factory()->create(['status' => 'rejected']);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('publishedOffersCount', 2)
            );
    }

    public function test_home_page_does_not_expose_the_offers_themselves(): void
    {
        JobOffer::factory()->create(['status' => 'published']);

        $this->get('/')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->missing('jobOffers')
            );
    }

    public function test_logging_out_leads_to_the_public_home_page(): void
    {
        $user = User::factory()->candidat()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect('/');

        $this->assertGuest();

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Welcome'));
    }

    public function test_connected_users_are_still_redirected_according_to_their_role(): void
    {
        $this->actingAs(User::factory()->candidat()->create())
            ->get('/')
            ->assertRedirect(route('jobs.index'));

        $this->actingAs(User::factory()->recruteur()->create())
            ->get('/')
            ->assertRedirect(route('job-offers.index'));

        $this->actingAs(User::factory()->admin()->create())
            ->get('/')
            ->assertRedirect(route('admin.moderation.index'));
    }
}
