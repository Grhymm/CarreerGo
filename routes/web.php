<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\JobOfferSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecruiterApplicationController;
use App\Http\Controllers\ReportController;
use App\Models\JobOffer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (! auth()->check()) {
        return Inertia::render('Welcome', [
            'publishedOffersCount' => JobOffer::where('status', 'published')->count(),
        ]);
    }

    return match (auth()->user()->role) {
        'recruteur' => redirect()->route('job-offers.index'),
        'admin' => redirect()->route('admin.moderation.index'),
        default => redirect()->route('jobs.index'),
    };
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:recruteur'])->group(function () {
    Route::resource('job-offers', JobOfferController::class)->except('show');

    Route::get('/recruiter/applications', [RecruiterApplicationController::class, 'index'])->name('recruiter.applications.index');
    Route::patch('/recruiter/applications/{application}', [RecruiterApplicationController::class, 'update'])->name('recruiter.applications.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/jobs', [JobOfferSearchController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{jobOffer}', [JobOfferSearchController::class, 'show'])->name('jobs.show');
});

Route::middleware(['auth', 'role:candidat'])->group(function () {
    Route::post('/jobs/{jobOffer}/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');

    Route::put('/profile/skills', [ProfileController::class, 'updateSkills'])->name('profile.skills.update');
    Route::resource('experiences', ExperienceController::class)->only(['store', 'update', 'destroy']);

    Route::get('/profile/cv', [CvController::class, 'download'])->name('profile.cv.download');

    Route::post('/jobs/{jobOffer}/reports', [ReportController::class, 'store'])->name('reports.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
    Route::patch('/moderation/{jobOffer}', [ModerationController::class, 'update'])->name('moderation.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-suspension', [UserController::class, 'toggleSuspension'])->name('users.toggle-suspension');
    Route::patch('/companies/{company}/toggle-verification', [CompanyController::class, 'toggleVerification'])->name('companies.toggle-verification');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}', [AdminReportController::class, 'update'])->name('reports.update');
});

require __DIR__.'/auth.php';
