<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Store a newly created experience for the authenticated candidate.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateExperience($request);

        $request->user()->experiences()->create($validated);

        return back();
    }

    /**
     * Update an existing experience owned by the authenticated candidate.
     */
    public function update(Request $request, Experience $experience): RedirectResponse
    {
        if ($experience->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $this->validateExperience($request);

        $experience->update($validated);

        return back();
    }

    /**
     * Delete an experience owned by the authenticated candidate.
     */
    public function destroy(Request $request, Experience $experience): RedirectResponse
    {
        if ($experience->user_id !== $request->user()->id) {
            abort(403);
        }

        $experience->delete();

        return back();
    }

    /**
     * Validate the incoming experience request data.
     *
     * @return array<string, mixed>
     */
    private function validateExperience(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
    }
}
