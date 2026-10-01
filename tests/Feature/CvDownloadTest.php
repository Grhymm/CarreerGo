<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CvDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_download_their_cv_as_pdf(): void
    {
        $candidate = User::factory()->candidat()->create(['name' => 'Élodie Mba']);
        $candidate->experiences()->create([
            'title' => 'Comptable',
            'company_name' => 'Banque Akanda',
            'start_date' => '2022-01-01',
        ]);

        $response = $this->actingAs($candidate)->get(route('profile.cv.download'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_cv_template_escapes_user_data(): void
    {
        $candidate = User::factory()->candidat()->create(['name' => '<script>alert(1)</script>']);
        $candidate->experiences()->create([
            'title' => '<b>Poste</b>',
            'company_name' => 'Entreprise & Cie',
            'start_date' => '2022-01-01',
        ]);

        $html = view('pdf.cv', [
            'user' => $candidate,
            'skills' => $candidate->skills,
            'experiences' => $candidate->experiences()->get(),
        ])->render();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<b>Poste</b>', $html);
        $this->assertStringContainsString('Entreprise &amp; Cie', $html);
    }
}
