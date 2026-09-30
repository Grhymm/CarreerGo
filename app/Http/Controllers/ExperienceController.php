<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateExperience($request);

        $request->user()->experiences()->create($validated);

        return back();
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        if ($experience->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $this->validateExperience($request);

        $experience->update($validated);

        return back();
    }

    public function destroy(Request $request, Experience $experience): RedirectResponse
    {
        if ($experience->user_id !== $request->user()->id) {
            abort(403);
        }

        $experience->delete();

        return back();
    }

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
