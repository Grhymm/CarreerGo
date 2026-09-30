<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class JobOfferSearchController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(['keyword', 'location', 'contract_type']);

        $jobOffers = JobOffer::query()
            ->with('company')
            ->where('status', 'published')
            ->when($filters['keyword'] ?? null, fn ($query, $keyword) => $query->where('title', 'like', "%{$keyword}%"))
            ->when($filters['location'] ?? null, fn ($query, $location) => $query->where('location', 'like', "%{$location}%"))
            ->when($filters['contract_type'] ?? null, fn ($query, $contractType) => $query->where('contract_type', $contractType))
            ->latest()
            ->get();

        return Inertia::render('Jobs/Index', [
            'jobOffers' => $jobOffers,
            'filters' => $filters,
        ]);
    }

    public function show(Request $request, JobOffer $jobOffer): Response
    {
        abort_unless($jobOffer->status === 'published', 404);

        $jobOffer->load(['company', 'skills', 'user']);

        $hasApplied = $jobOffer->applications()
            ->where('user_id', $request->user()->id)
            ->exists();

        return Inertia::render('Jobs/Show', [
            'jobOffer' => $jobOffer,
            'compatibilityScore' => $jobOffer->compatibilityScoreFor($request->user()),
            'hasApplied' => $hasApplied,
        ]);
    }
}
