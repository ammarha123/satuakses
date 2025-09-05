<?php
namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $company = Company::where('user_id', Auth::id())->first();

        $applications = Application::with(['user','lowongan'])
            ->when($company, fn($q) => $q->where('company_id', $company->id))
            ->latest('submitted_at')
            ->paginate(15);

        return view('employer.applications.index', compact('applications','company'));
    }

    public function show(Application $application)
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        abort_if($application->company_id !== $company->id, 403);

        $application->load(['user','lowongan']);
        return view('employer.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:submitted,reviewed,shortlisted,rejected,hired',
        ]);

        $company = Company::where('user_id', Auth::id())->firstOrFail();
        abort_if($application->company_id !== $company->id, 403);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Status lamaran diperbarui.');
    }
}
