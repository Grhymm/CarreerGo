<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecruiterApplicationController extends Controller
{
    public function index(Request $request): Response
    {
        $applications = Application::query()
            ->whereHas('jobOffer', fn ($query) => $query->where('user_id', $request->user()->id))
            ->with(['user.skills', 'jobOffer.skills'])
            ->latest()
            ->get()
            ->map(fn (Application $application) => [
                'id' => $application->id,
                'status' => $application->status,
                'applied_at' => $application->applied_at,
                'candidate_name' => $application->user->name,
                'job_offer_title' => $application->jobOffer->title,
                'compatibility_score' => $application->jobOffer->compatibilityScoreFor($application->user),
            ]);

        return Inertia::render('Recruiter/Applications/Index', [
            'applications' => $applications,
        ]);
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        if ($application->jobOffer->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:new,reviewing,interview,rejected,accepted'],
        ]);

        $application->update($validated);

        return back();
    }
}
