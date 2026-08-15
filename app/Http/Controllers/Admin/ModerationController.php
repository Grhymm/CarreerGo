<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ModerationController extends Controller
{
    /**
     * Display the queue of job offers awaiting moderation.
     */
    public function index(): Response
    {
        $jobOffers = JobOffer::with(['company', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return Inertia::render('Admin/Moderation/Index', [
            'jobOffers' => $jobOffers,
        ]);
    }

    /**
     * Approve or reject a pending job offer.
     */
    public function update(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->status !== 'pending') {
            abort(409);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:published,rejected'],
        ]);

        $jobOffer->update($validated);

        return redirect()->route('admin.moderation.index');
    }
}
