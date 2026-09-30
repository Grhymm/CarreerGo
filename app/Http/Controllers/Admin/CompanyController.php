<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{
    public function toggleVerification(Company $company): RedirectResponse
    {
        $company->update(['is_verified' => ! $company->is_verified]);

        return back();
    }
}
