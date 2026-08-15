<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Report a job offer as suspicious.
     */
    public function store(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $jobOffer->reports()->create([
            'reported_by' => $request->user()->id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre signalement a été envoyé.');
    }
}
