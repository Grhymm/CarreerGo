<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    public function index(Request $request): Response
    {
        $applications = $request->user()
            ->applications()
            ->with('jobOffer.company')
            ->latest()
            ->get();

        return Inertia::render('Applications/Index', [
            'applications' => $applications,
        ]);
    }

    public function store(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        abort_unless($jobOffer->status === 'published', 404);

        $alreadyApplied = $jobOffer->applications()
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $jobOffer->applications()->create([
            'user_id' => $request->user()->id,
            'status' => 'new',
            'applied_at' => now(),
        ]);

        return back()->with('success', 'Votre candidature a été envoyée.');
    }
}
