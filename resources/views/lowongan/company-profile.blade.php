@extends('layouts.app')

@section('title', 'Profil Perusahaan — ' . ($company->name ?? 'Perusahaan'))

@section('content')
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="text-decoration-none mb-3 d-inline-block">← Kembali</a>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        @if($company->logo_path)
                            <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="Logo" class="rounded" style="width:96px;height:96px;object-fit:cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:96px;height:96px">
                                <i class="bi bi-building fs-3 text-muted"></i>
                            </div>
                        @endif

                        <div>
                            <h3 class="mb-0">{{ $company->name }}</h3>
                            @if($company->slug)
                                <div class="small text-muted">Slug: {{ $company->slug }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Email</strong>
                            <div class="small">
                                @if($company->email)
                                    <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Telepon</strong>
                            <div class="small">
                                @if($company->phone)
                                    <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Website</strong>
                            <div class="small">
                                @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener">{{ $company->website }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Alamat</strong>
                            <div class="small text-muted">{{ $company->address ?? ($company->provinsi ?? '') . ' ' . ($company->city ?? '') }}</div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-1">Deskripsi</h6>
                    <p class="text-muted" style="white-space:pre-line;">{{ $company->description ?? '-' }}</p>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">Terdaftar: {{ optional($company->created_at)->format('d M Y') }}</div>
                        @if(method_exists($company, 'lowongans') && $company->lowongans()->exists())
                            <a href="{{ route('lowongan.index', ['company' => $company->name]) }}" class="btn btn-outline-primary btn-sm">
                                Lihat Lowongan ({{ $company->lowongans()->count() }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- right column: quick stats or contact card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Kontak</h6>
                    <p class="mb-1">
                        <strong>Email:</strong><br>
                        @if($company->email)
                            <a href="mailto:{{ $company->email }}" class="small">{{ $company->email }}</a>
                        @else
                            <small class="text-muted">-</small>
                        @endif
                    </p>

                    <p class="mb-1">
                        <strong>Telepon:</strong><br>
                        @if($company->phone)
                            <a href="tel:{{ $company->phone }}" class="small">{{ $company->phone }}</a>
                        @else
                            <small class="text-muted">-</small>
                        @endif
                    </p>

                    @if($company->website)
                        <p class="mb-0">
                            <a href="{{ $company->website }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Kunjungi Website</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
