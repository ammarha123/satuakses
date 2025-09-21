@extends('adminlte::page')

@section('title', 'Detail Lowongan')

@section('content_header')
    <h1 class="mb-3">Detail Lowongan</h1>
@endsection

@section('content')
    <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">{{ $lowongan->posisi }}</h3>
            <span class="badge badge-light ml-2">{{ $lowongan->status ?? 'Draft' }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Perusahaan:</strong> {{ $lowongan->perusahaan }}</p>
                    <p><strong>Kategori:</strong> {{ $lowongan->kategori->nama ?? '-' }}</p>
                    <p><strong>Lokasi:</strong> {{ $lowongan->lokasi }}</p>
                    <p><strong>Tanggal Posting:</strong> 
                        {{ $lowongan->waktu_posting ? $lowongan->waktu_posting->format('d M Y') : '-' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p>
                        <strong>Gaji Minimum:</strong> 
                        {{ $lowongan->gaji_min ? 'Rp ' . number_format($lowongan->gaji_min, 0, ',', '.') : '-' }}
                    </p>
                    <p>
                        <strong>Gaji Maksimum:</strong> 
                        {{ $lowongan->gaji_max ? 'Rp ' . number_format($lowongan->gaji_max, 0, ',', '.') : '-' }}
                    </p>
                    <p><strong>Kuota:</strong> {{ $lowongan->kuota ?? '-' }}</p>
                    <p><strong>Batas Lamaran:</strong> 
                        {{ $lowongan->batas_lamaran ? \Carbon\Carbon::parse($lowongan->batas_lamaran)->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>

            <hr>

            <h5><i class="fas fa-file-alt"></i> Deskripsi</h5>
            <p>{{ $lowongan->dekskripsi }}</p>

            <h5><i class="fas fa-check-circle"></i> Persyaratan</h5>
            <ul>
                @foreach (explode("\n", $lowongan->persyaratan ?? '') as $line)
                    @if (trim($line) !== '')
                        <li>{{ $line }}</li>
                    @endif
                @endforeach
            </ul>

            <h5><i class="fas fa-universal-access"></i> Fasilitas Disabilitas</h5>
            <ul>
                @foreach (explode("\n", $lowongan->fasilitas_disabilitas ?? '') as $line)
                    @if (trim($line) !== '')
                        <li>{{ $line }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
@endsection
