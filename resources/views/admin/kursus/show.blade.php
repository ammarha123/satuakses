@extends('adminlte::page')

@section('title', 'Detail Kursus')

@section('content_header')
    <h1>Detail Kursus</h1>
@endsection

@section('content')
    <div class="mb-3"><strong>Judul:</strong> {{ $kursus->judul }}</div>
    <div class="mb-3"><strong>Kategori:</strong> {{ $kursus->kategori }}</div>
    <div class="mb-3"><strong>Tingkat:</strong> {{ $kursus->tingkat ?? '-' }}</div>
    <div class="mb-3"><strong>Tanggal Mulai:</strong> {{ $kursus->tanggal_mulai?->format('d M Y') ?? '-' }}</div>
    <div class="mb-3"><strong>Deskripsi:</strong> <br> {!! nl2br(e($kursus->deskripsi)) !!}</div>

    <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary">Kembali</a>
@endsection
