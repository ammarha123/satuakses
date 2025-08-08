@extends('adminlte::page')

@section('title', 'Detail Lowongan')

@section('content_header')
    <h1>Detail Lowongan</h1>
@endsection

@section('content')
    <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <div class="card">
        <div class="card-body">
            <p><strong>Perusahaan:</strong> {{ $lowongan->perusahaan }}</p>
            <p><strong>Posisi:</strong> {{ $lowongan->posisi }}</p>
            <p><strong>Dekskripsi:</strong> {{ $lowongan->dekskripsi }}</p>
            <p><strong>Lokasi:</strong> {{ $lowongan->lokasi }}</p>
            <p><strong>Kategori:</strong> {{ $lowongan->kategori ?? '-' }}</p>
            <p><strong>Tanggal Posting:</strong> {{ $lowongan->waktu_posting->format('d M Y') }}</p>
            <p><strong>Status:</strong> {{ $lowongan->status }}</p>
        </div>
    </div>
@endsection
