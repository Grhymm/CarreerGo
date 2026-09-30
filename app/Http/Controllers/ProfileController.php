<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Skill;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $isCandidat = $user->role === 'candidat';

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'skills' => $isCandidat ? $user->skills : [],
            'experiences' => $isCandidat ? $user->experiences()->orderByDesc('start_date')->get() : [],
        ]);
    }

    public function updateSkills(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skills' => ['array'],
            'skills.*' => ['string', 'max:255'],
        ]);

        $skillIds = collect($validated['skills'] ?? [])
            ->filter()
            ->map(fn (string $name) => Skill::firstOrCreate(['name' => $name])->id);

        $request->user()->skills()->sync($skillIds);

        return back();
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
