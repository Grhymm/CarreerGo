<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\User;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\DemoUsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_can_be_replayed_without_duplicates_or_touching_accounts(): void
    {
        $this->seed(DemoUsersSeeder::class);

        $legacyOffer = JobOffer::factory()->create(['location' => 'Rabat']);
        $accountsBefore = User::orderBy('id')->get(['id', 'email', 'password', 'role', 'company_id'])->toArray();

        $this->seed(DemoContentSeeder::class);
        $this->seed(DemoContentSeeder::class);

        $this->assertSame($accountsBefore, User::orderBy('id')->get(['id', 'email', 'password', 'role', 'company_id'])->toArray());

        $this->assertSame(4, Company::where('name', 'like', '%(démo)')->count());
        $demoOffers = JobOffer::whereHas('company', fn ($query) => $query->where('name', 'like', '%(démo)'))->get();
        $this->assertCount(8, $demoOffers);
        $this->assertEqualsCanonicalizing(['CDI', 'CDD', 'Stage', 'Freelance'], $demoOffers->pluck('contract_type')->unique()->values()->all());
        $this->assertEmpty(array_diff($demoOffers->pluck('location')->unique()->all(), ['Libreville', 'Port-Gentil', 'Franceville', 'Oyem']));
        $this->assertTrue($demoOffers->every(fn (JobOffer $offer) => $offer->skills()->exists()));

        $candidate = User::where('email', 'candidat@carreergo.test')->first();
        $this->assertSame(5, Application::where('user_id', $candidate->id)->count());
        $this->assertSame(5, Application::where('user_id', $candidate->id)->distinct()->count('status'));

        $this->assertSame('Libreville', $legacyOffer->fresh()->location);
        $this->assertSame(0, JobOffer::where('location', 'Rabat')->count());
    }

    public function test_seeder_does_nothing_without_demo_accounts(): void
    {
        $this->seed(DemoContentSeeder::class);

        $this->assertSame(0, Company::count());
        $this->assertSame(0, JobOffer::count());
    }
}
