<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class KursusController extends Controller
{
     public function index()
    {
        $kursus = Course::latest()->paginate(10);
        return view('admin.kursus.index', compact('kursus'));
    }

    public function create()
    {
        return view('admin.kursus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:100',
            'tingkat' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil ditambahkan.');
    }

    public function show(Course $kursus)
    {
        return view('admin.kursus.show', compact('kursus'));
    }

    public function edit(Course $kursus)
    {
        return view('admin.kursus.edit', compact('kursus'));
    }

    public function update(Request $request, Course $kursus)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:100',
            'tingkat' => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
        ]);

        $kursus->update($request->all());

        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil diperbarui.');
    }

    public function destroy(Course $kursus)
    {
        $kursus->delete();

        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil dihapus.');
    }
}
