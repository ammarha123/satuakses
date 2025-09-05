<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::query()->where('is_terbuka', 1);

        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('posisi', 'like', "%$q%")
                    ->orWhere('dekskripsi', 'like', "%$q%");
            });
        }

        if ($request->filled('facility')) {
            foreach ((array) $request->facility as $fac) {
                $query->where('fasilitas_disabilitas', 'like', "%$fac%");
            }
        }

        if ($request->filled('edu')) {
            foreach ((array) $request->edu as $edu) {
                $query->where('persyaratan', 'like', "%$edu%");
            }
        }

        if ($request->sort === 'baru') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $lowongans = $query->paginate(10);

        return view('lowongan.index', compact('lowongans'));
    }
    
    public function show(string $slug)
    {
        $lowongan = Lowongan::where('slug', $slug)->firstOrFail();

        $hasApplied = false;
        if (auth()->check() && auth()->user()->hasRole('user')) {
            $hasApplied = Application::where('user_id', auth()->id())
                ->where('lowongan_id', $lowongan->id)
                ->exists();
        }

        return view('lowongan-detail', compact('lowongan', 'hasApplied'));
    }
}
