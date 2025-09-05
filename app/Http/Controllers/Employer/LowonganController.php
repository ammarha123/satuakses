<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LowonganController extends Controller
{
    public function index()
    {
        $company = Company::where('user_id', Auth::id())->first();
        $lowongans = Lowongan::with('kategori')
            ->when($company, fn($q) => $q->where('perusahaan', $company->name))
            ->latest()->paginate(10);

        return view('employer.lowongan.index', compact('lowongans','company'));
    }

    public function create()
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        $kategoriLowongans = KategoriLowongan::orderBy('nama')->get();
        return view('employer.lowongan.create', compact('company','kategoriLowongans'));
    }

    public function store(Request $request)
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'posisi'             => 'required|string|max:255',
            'slug'               => 'nullable|string|unique:lowongans,slug',
            'dekskripsi'         => 'required|string',
            'lokasi'             => 'required|string|max:255',
            'kategori_id'        => 'required|exists:kategori_lowongans,id',
            'tipe_pekerjaan'     => 'nullable|in:Full-time,Part-time,Remote,Hybrid',
            'persyaratan'        => 'nullable|string',
            'fasilitas_disabilitas' => 'nullable|string',
            'gaji_min'           => 'nullable|numeric',
            'gaji_max'           => 'nullable|numeric',
            'kuota'              => 'nullable|integer',
            'batas_lamaran'      => 'nullable|date',
            'waktu_posting'      => 'nullable|date',
            'status'             => 'nullable|string',
            'is_terbuka'         => 'nullable|boolean',
            'posting_option'     => 'nullable|in:now,schedule',
        ]);
        $validated['perusahaan'] = $company->name;

        if (($validated['posting_option'] ?? null) === 'now') {
            $validated['waktu_posting'] = now();
        }
        unset($validated['posting_option']);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['posisi'].'-'.Str::random(5));
        $validated['status'] = $validated['status'] ?? 'Active';

        Lowongan::create($validated);

        return redirect()->route('employer.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lowongan = $this->findOwnedLowongan($id);
        return view('employer.lowongan.show', compact('lowongan'));
    }

    public function edit($id)
    {
        $lowongan = $this->findOwnedLowongan($id);
        $kategoriLowongans = KategoriLowongan::orderBy('nama')->get();
        return view('employer.lowongan.edit', compact('lowongan','kategoriLowongans'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = $this->findOwnedLowongan($id);

        $validated = $request->validate([
            'posisi'             => 'required|string|max:255',
            'slug'               => 'nullable|string|unique:lowongans,slug,'.$lowongan->id,
            'dekskripsi'         => 'required|string',
            'lokasi'             => 'required|string|max:255',
            'kategori_id'        => 'required|exists:kategori_lowongans,id',
            'tipe_pekerjaan'     => 'nullable|in:Full-time,Part-time,Remote,Hybrid',
            'persyaratan'        => 'nullable|string',
            'fasilitas_disabilitas' => 'nullable|string',
            'gaji_min'           => 'nullable|numeric',
            'gaji_max'           => 'nullable|numeric',
            'kuota'              => 'nullable|integer',
            'batas_lamaran'      => 'nullable|date',
            'waktu_posting'      => 'nullable|date',
            'status'             => 'nullable|string',
            'is_terbuka'         => 'nullable|boolean',
            'posting_option'     => 'nullable|in:now,schedule',
        ]);

        if (($validated['posting_option'] ?? null) === 'now') {
            $validated['waktu_posting'] = now();
        }
        unset($validated['posting_option']);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['posisi'].'-'.Str::random(5));

        $lowongan->update($validated);

        return redirect()->route('employer.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lowongan = $this->findOwnedLowongan($id);
        $lowongan->delete();

        return redirect()->route('employer.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }

    private function findOwnedLowongan($id): Lowongan
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();

        return Lowongan::where('perusahaan', $company->name)
            ->where('id', $id)
            ->firstOrFail();
    }
}
