@extends('adminlte::page')

@section('title', 'Detail Lowongan')

@section('content_header')
    <h1>Detail Lowongan</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-1">{{ $lowongan->posisi }}</h4>
            <div class="text-muted mb-3">{{ $lowongan->perusahaan }} • {{ $lowongan->lokasi }}</div>

            <p><b>Kategori:</b> {{ $lowongan->kategori->nama ?? '-' }}</p>
            <p><b>Tipe Pekerjaan:</b> {{ $lowongan->tipe_pekerjaan ?? '-' }}</p>
            <p><b>Status:</b> {{ $lowongan->status }}</p>
            <p><b>Gaji:</b>
                @if($lowongan->gaji_min || $lowongan->gaji_max)
                    Rp {{ number_format($lowongan->gaji_min ?? 0,0,',','.') }} -
                    Rp {{ number_format($lowongan->gaji_max ?? 0,0,',','.') }}
                @else
                    -
                @endif
            </p>
            <p><b>Kuota:</b> {{ $lowongan->kuota ?? '-' }}</p>
            <p><b>Batas Lamaran:</b> {{ optional($lowongan->batas_lamaran)->format('d M Y') ?? '-' }}</p>
            <p><b>Diposting:</b> {{ optional($lowongan->waktu_posting ?? $lowongan->created_at)->diffForHumans() }}</p>

            <hr>
            <h6>Deskripsi</h6>
            <div>{!! nl2br(e($lowongan->dekskripsi)) !!}</div>

            @if(!empty($lowongan->persyaratan))
                <hr>
                <h6>Persyaratan</h6>
                <div>{!! nl2br(e($lowongan->persyaratan)) !!}</div>
            @endif

            @if(!empty($lowongan->fasilitas_disabilitas))
                <hr>
                <h6>Fasilitas Disabilitas</h6>
                <div>{!! nl2br(e($lowongan->fasilitas_disabilitas)) !!}</div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('employer.lowongan.edit', $lowongan->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('employer.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
