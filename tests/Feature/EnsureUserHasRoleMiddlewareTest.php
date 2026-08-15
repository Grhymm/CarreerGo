<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureUserHasRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_candidat_is_forbidden_from_an_admin_route(): void
    {
        $candidat = User::factory()->candidat()->create();

        $response = $this->actingAs($candidat)->get('/admin/moderation');

        $response->assertForbidden();
    }

    public function test_an_admin_can_access_an_admin_route(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/moderation');

        $response->assertOk();
    }
}
