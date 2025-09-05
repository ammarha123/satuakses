<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    public function index()
    {
        $apps = Application::with(['lowongan', 'company'])
            ->where('user_id', Auth::id())
            ->latest('submitted_at')
            ->paginate(12);

        return view('user.lamaran.index', compact('apps'));
    }

    public function destroy(Application $application)
    {
        abort_unless($application->user_id === Auth::id(), 403);

        if (in_array($application->status, ['reviewed', 'accepted', 'rejected'])) {
            return back()->with('error', 'Lamaran sudah diproses dan tidak bisa dibatalkan.');
        }

        if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
            Storage::disk('public')->delete($application->cv_path);
        }

        $application->delete();

        return back()->with('success', 'Lamaran dibatalkan.');
    }
    
    public function store(Request $request, Lowongan $lowongan)
    {
        $request->validate([
            'cover_letter' => 'nullable|string',
            'cv'           => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if (Application::where('user_id', Auth::id())
            ->where('lowongan_id', $lowongan->id)->exists()
        ) {
            return back()->with('error', 'Kamu sudah melamar lowongan ini.');
        }

        if (!$lowongan->is_terbuka || $lowongan->status === 'Closed') {
            return back()->with('error', 'Lowongan ini sudah tidak menerima lamaran.');
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cv', 'public');
        }

        $companyId = Company::where('name', $lowongan->perusahaan)->value('id');

        Application::create([
            'user_id'      => Auth::id(),
            'lowongan_id'  => $lowongan->id,
            'company_id'   => $companyId,
            'cover_letter' => $request->cover_letter,
            'cv_path'      => $cvPath,
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Lamaran berhasil dikirim!');
    }
}