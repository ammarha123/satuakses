<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LowonganPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::query();

       if ($request->filled('lokasi')) {
        $query->where('lokasi', $request->lokasi);
    }

    $lowongans = $query->latest()->paginate(10);

    // get distinct lokasi list for select options
    $lokasis = Lowongan::select('lokasi')
        ->whereNotNull('lokasi')
        ->distinct()
        ->pluck('lokasi');

        if ($q = $request->input('q')) {
            $query->where(function ($qr) use ($q) {
                $qr->where('posisi', 'like', '%' . $q . '%')
                    ->orWhere('perusahaan', 'like', '%' . $q . '%');
            });
        }

        if ($dis = $request->input('disability')) {
            $query->where('fasilitas_disabilitas', 'like', '%' . $dis . '%');
        }

        // salary range
        $salaryMin = $request->input('salary_min') ? intval(preg_replace('/\D/', '', $request->input('salary_min'))) : null;
        $salaryMax = $request->input('salary_max') ? intval(preg_replace('/\D/', '', $request->input('salary_max'))) : null;

        if ($salaryMin !== null) {
            $query->where(function ($q) use ($salaryMin) {
                $q->where('gaji_min', '>=', $salaryMin)
                    ->orWhere('gaji_max', '>=', $salaryMin);
            });
        }

        if ($salaryMax !== null) {
            $query->where(function ($q) use ($salaryMax) {
                $q->where('gaji_min', '<=', $salaryMax)
                    ->orWhere('gaji_max', '<=', $salaryMax);
            });
        }

        // category filter (if user selected one)
        if ($kategori = $request->input('kategori')) {
            // try match by kategori id OR kategori name
            if (is_numeric($kategori)) {
                $query->where('kategori_id', intval($kategori));
            } else {
                // if you store kategori as text in lowongans table
                $query->where(function ($q) use ($kategori) {
                    $q->where('kategori', 'like', '%' . $kategori . '%')
                        ->orWhere('kategori_id', $kategori);
                });
            }
        }

        // sorting
        switch ($request->input('sort')) {
            case 'baru':
                $query->orderBy('created_at', 'desc');
                break;
            case 'gaji_min_asc':
                $query->orderBy('gaji_min', 'asc');
                break;
            case 'gaji_min_desc':
                $query->orderBy('gaji_min', 'desc');
                break;
            case 'gaji_max_asc':
                $query->orderBy('gaji_max', 'asc');
                break;
            case 'gaji_max_desc':
                $query->orderBy('gaji_max', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }



        // categories - try three fallbacks:
        // 1) If there's a kategori text column on lowongans, use that.
        // 2) Else if there's kategori_id and a kategori_lowongans table, fetch names from that table.
        // 3) Else pluck distinct kategori_id (as fallback).
        $categories = collect();

        if (Schema::hasColumn('lowongans', 'kategori')) {
            $categories = Lowongan::query()
                ->whereNotNull('kategori')
                ->pluck('kategori')
                ->filter(fn($v) => trim($v) !== '')
                ->unique()
                ->sort()
                ->values();
        } elseif (Schema::hasColumn('lowongans', 'kategori_id') && Schema::hasTable('kategori_lowongans')) {
            // join to kategori_lowongans to get readable names
            $categories = DB::table('kategori_lowongans')
                ->join('lowongans', 'kategori_lowongans.id', '=', 'lowongans.kategori_id')
                ->select('kategori_lowongans.id', 'kategori_lowongans.nama')
                ->distinct()
                ->orderBy('kategori_lowongans.nama')
                ->get()
                ->mapWithKeys(fn($r) => [$r->id => $r->nama]);
            // result is a collection keyed by id => name
        } elseif (Schema::hasColumn('lowongans', 'kategori_id')) {
            $categories = Lowongan::query()
                ->whereNotNull('kategori_id')
                ->pluck('kategori_id')
                ->unique()
                ->values();
        }

        $companyNames = $lowongans->pluck('perusahaan')
    ->filter()
    ->map(fn($n) => trim($n))
    ->unique()
    ->all();

    $companies = Company::whereIn('name', $companyNames)->get();
    $companyMap = $companies->keyBy(fn($c) => trim($c->name));

        $lowongans = $query->paginate(12);

        return view('lowongan.index', compact('lowongans', 'lokasis', 'categories', 'companyMap'));
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

    public function showCompany(Company $company)
    {
        // eager load anything needed, e.g. jobs count
        $company->loadCount('lowongans'); // if relation exists
        return view('lowongan.company-profile', compact('company'));
    }
}
