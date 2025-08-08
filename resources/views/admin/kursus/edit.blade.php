@extends('adminlte::page')

@section('title', 'Edit Kursus')

@section('content_header')
    <h1>Edit Kursus</h1>
@endsection

@section('content')
    <form action="{{ route('admin.kursus.update', $kursus) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $kursus->judul) }}" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $kursus->deskripsi) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $kursus->kategori) }}" required>
        </div>
        <div class="mb-3">
            <label>Tingkat</label>
            <input type="text" name="tingkat" class="form-control" value="{{ old('tingkat', $kursus->tingkat) }}">
        </div>
        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $kursus->tanggal_mulai?->format('Y-m-d') }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
