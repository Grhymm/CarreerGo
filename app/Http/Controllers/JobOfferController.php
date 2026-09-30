<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class JobOfferController extends Controller
{
    public function index(Request $request): Response
    {
        $jobOffers = $request->user()->jobOffers()->latest()->get();

        return Inertia::render('JobOffers/Index', [
            'jobOffers' => $jobOffers,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('JobOffers/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateJobOffer($request);

        $jobOffer = $request->user()->jobOffers()->create([
            ...$validated,
            'company_id' => $request->user()->company_id,
            'status' => 'pending',
        ]);

        $this->syncSkills($jobOffer, $request->input('skills', []));

        return redirect()->route('job-offers.index');
    }

    public function edit(Request $request, JobOffer $jobOffer): Response
    {
        if ($jobOffer->user_id !== $request->user()->id) {
            abort(403);
        }

        return Inertia::render('JobOffers/Edit', [
            'jobOffer' => $jobOffer->load('skills'),
        ]);
    }

    public function update(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $this->validateJobOffer($request);

        $jobOffer->update($validated);

        $this->syncSkills($jobOffer, $request->input('skills', []));

        return redirect()->route('job-offers.index');
    }

    public function destroy(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->user_id !== $request->user()->id) {
            abort(403);
        }

        $jobOffer->delete();

        return redirect()->route('job-offers.index');
    }

    private function validateJobOffer(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'contract_type' => ['required', 'in:CDI,CDD,Freelance,Stage'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'gte:salary_min'],
            'show_salary' => ['boolean'],
            'stage_duration_months' => ['nullable', 'integer', 'min:1', 'required_if:contract_type,Stage'],
            'stage_stipend' => ['nullable', 'integer', 'min:0', 'required_if:contract_type,Stage'],
            'guaranteed_response' => ['boolean'],
        ]);
    }

    private function syncSkills(JobOffer $jobOffer, array $skillNames): void
    {
        $skillIds = collect($skillNames)
            ->filter()
            ->map(fn (string $name) => Skill::firstOrCreate(['name' => $name])->id);

        $jobOffer->skills()->sync($skillIds);
    }
}
