<?php
namespace App\Http\Controllers;

use App\Models\WorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkExperienceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company'     => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_current'  => 'nullable',
            'description' => 'nullable|string|max:2000',
        ]);
        $isCurrent = $request->boolean('is_current'); 
        $validated['is_current'] = $isCurrent;
        $validated['end_date'] = $isCurrent ? null : ($validated['end_date'] ?? null);

        $validated['user_id'] = Auth::id();

        WorkExperience::create($validated);

        return back()->with('success', 'Pengalaman kerja berhasil ditambahkan.');
    }

    public function update(Request $request, WorkExperience $experience)
{
    if ($experience->user_id !== Auth::id()) {
        abort(403, 'You are not allowed to update this resource.');
    }

    // 2) validate input
    $validated = $request->validate([
        'company'     => 'required|string|max:255',
        'position'    => 'required|string|max:255',
        'start_date'  => 'required|date',
        'end_date'    => 'nullable|date|after_or_equal:start_date',
        'is_current'  => 'nullable',
        'description' => 'nullable|string|max:2000',
    ]);

    $isCurrent = $request->boolean('is_current');  
    $validated['is_current'] = $isCurrent ? 1 : 0;        
    $validated['end_date'] = $isCurrent ? null : ($validated['end_date'] ?? null);

    if (!empty($validated['start_date'])) {
        $validated['start_date'] = \Carbon\Carbon::parse($validated['start_date'])->format('Y-m-d');
    }
    if (!empty($validated['end_date'])) {
        $validated['end_date'] = \Carbon\Carbon::parse($validated['end_date'])->format('Y-m-d');
    }

    $experience->update($validated);

    return redirect()->route('profile.edit')->with('success', 'Pengalaman kerja berhasil diperbarui.');
}

public function edit(WorkExperience $experience)
{
    if ($experience->user_id !== Auth::id()) {
        abort(403);
    }
    return view('profile.edit-work-experience', compact('experience'));
}


    public function destroy(WorkExperience $experience)
    {
        $experience->delete();
        return back()->with('success', 'Pengalaman kerja berhasil dihapus.');
    }
}
