<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index()
    {
        $lowongans = Lowongan::latest()->paginate(10);
        return view('admin.lowongan.index', compact('lowongans'));
    }

    public function create()
    {
        return view('admin.lowongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'dekskripsi' => 'required|string|max:255',
            'posting_option' => 'required|in:now,schedule',
            'waktu_posting' => 'nullable|date|after_or_equal:today|before_or_equal:' . now()->addMonth()->toDateString(),
        ]);

        $data = $request->only(['perusahaan', 'kategori', 'lokasi', 'posisi']);

        if ($request->posting_option === 'now') {
            $data['waktu_posting'] = now();
            $data['status'] = 'Active';
        } else {
            $data['waktu_posting'] = $request->waktu_posting;
            $data['status'] = 'Scheduled Posting';
        }

        Lowongan::create($data);

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function show(Lowongan $lowongan)
    {
        return view('admin.lowongan.show', compact('lowongan'));
    }

    public function edit(Lowongan $lowongan)
    {
        return view('admin.lowongan.edit', compact('lowongan'));
    }

    public function update(Request $request, Lowongan $lowongan)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'dekskripsi' => 'required|string|max:255',
        ]);

        $lowongan->update($request->only('perusahaan', 'kategori', 'lokasi', 'posisi', 'dekskripsi'));

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }
}
