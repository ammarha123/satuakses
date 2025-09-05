<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LowonganController extends Controller
{
    public function index()
    {
        $lowongans = Lowongan::latest()->paginate(10);
        return view('admin.lowongan.index', compact('lowongans'));
    }

    public function create()
    {
        $kategoriLowongans = KategoriLowongan::all();
        $companies = Company::orderBy('name')->get(['id','name']); 
        return view('admin.lowongan.create', compact('kategoriLowongans','companies'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            
            'company_id' => 'required|exists:companies,id', 
            'posisi' => 'required|string',
            'slug' => 'nullable|string|unique:lowongans,slug',
            'dekskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'tipe_pekerjaan' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'fasilitas_disabilitas' => 'nullable|string',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'kuota' => 'nullable|integer',
            'kategori_id' => 'required|exists:kategori_lowongans,id',
            'batas_lamaran' => 'nullable|date',
            'waktu_posting' => 'nullable|date',
            'status' => 'nullable|string',
            'is_terbuka' => 'nullable|boolean',
        ]);

      
        $company = Company::findOrFail($validated['company_id']);
        $validated['perusahaan'] = $company->name; 
        unset($validated['company_id']); 

        if ($request->posting_option === 'now') {
            $validated['waktu_posting'] = now();
        }

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['posisi'].'-'.Str::random(5));

        Lowongan::create($validated);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(Lowongan $lowongan)
    {
        $kategoriLowongans = KategoriLowongan::all();
        $companies = Company::orderBy('name')->get(['id','name']);

        $selectedCompanyId = Company::where('name', $lowongan->perusahaan)->value('id');

        return view('admin.lowongan.edit', compact('lowongan','kategoriLowongans','companies','selectedCompanyId'));
    }

    public function update(Request $request, Lowongan $lowongan)
    {
        $validated = $request->validate([
           
            'company_id' => 'required|exists:companies,id',
            'posisi' => 'required|string',
            'slug' => 'nullable|string|unique:lowongans,slug,'.$lowongan->id,
            'dekskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'tipe_pekerjaan' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'fasilitas_disabilitas' => 'nullable|string',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'kategori_id' => 'required|exists:kategori_lowongans,id',
            'kuota' => 'nullable|integer',
            'batas_lamaran' => 'nullable|date',
            'waktu_posting' => 'nullable|date',
            'status' => 'nullable|string',
            'is_terbuka' => 'nullable|boolean',
        ]);

        $company = Company::findOrFail($validated['company_id']);
        $validated['perusahaan'] = $company->name; // ✅ update nama perusahaan
        unset($validated['company_id']);

        if ($request->posting_option === 'now') {
            $validated['waktu_posting'] = now();
        }

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['posisi'].'-'.Str::random(5));

        $lowongan->update($validated);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function show(Lowongan $lowongan)
    {
        return view('admin.lowongan.show', compact('lowongan'));
    }
}
