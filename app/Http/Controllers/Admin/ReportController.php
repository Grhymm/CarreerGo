<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display the queue of reports awaiting review.
     */
    public function index(): Response
    {
        $reports = Report::with(['jobOffer.company', 'reporter'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return Inertia::render('Admin/Reports/Index', [
            'reports' => $reports,
        ]);
    }

    /**
     * Mark a report as reviewed or dismissed.
     */
    public function update(Request $request, Report $report): RedirectResponse
    {
        if ($report->status !== 'pending') {
            abort(409);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:reviewed,dismissed'],
        ]);

        $report->update([
            'status' => $validated['status'],
            'reviewed_by' => $request->user()->id,
        ]);

        return back();
    }
}
