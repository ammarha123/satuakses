@extends('adminlte::page')

@section('title', 'Tambah Kursus')

@section('content_header')
    <h1>Tambah Kursus</h1>
@endsection

@section('content')
    <form action="{{ route('admin.kursus.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tingkat (opsional)</label>
            <input type="text" name="tingkat" class="form-control">
        </div>
        <div class="mb-3">
            <label>Tanggal Mulai (opsional)</label>
            <input type="date" name="tanggal_mulai" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
