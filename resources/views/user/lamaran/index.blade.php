@extends('layouts.app')

@section('title','Lamaran Saya')

@section('content')
<style>
    .app-card {
        border: 1px solid #000101;
        border-radius: 16px;
        transition: .2s ease;
        box-shadow: 0 4px 22px rgba(0,0,0,.03);
        background-color: #CAEAEF;
    }
    .app-card:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,.06); }
    .company-avatar {
        width:48px;height:48px;border-radius:12px;
        display:flex;align-items:center;justify-content:center;
        font-weight:700;background:#ecf3ff;color:#2463eb;
    }
    .status-dot{width:8px;height:8px;border-radius:50%;}
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="fw-bold mb-0">Lamaran Saya</h4>
        <a href="{{ route('lowongan.index') }}" class="btn btn-primary">Cari Lowongan</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>@endif

    @if($apps->count() === 0)
        <div class="text-center text-muted py-5">
            <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/1f4bc.svg" alt="" width="48" class="mb-3">
            <div class="mb-2">Belum ada lamaran yang dikirim.</div>
            <a class="btn btn-outline-primary" href="{{ route('lowongan.index') }}">Lihat Lowongan</a>
        </div>
    @else
    <div class="row g-3">
        @php
            $badge = [
                'submitted' => 'secondary',
                'reviewed'  => 'info',
                'accepted'  => 'success',
                'rejected'  => 'danger',
            ];
            $dot = [
                'submitted' => '#6c757d',
                'reviewed'  => '#0dcaf0',
                'accepted'  => '#18a957',
                'rejected'  => '#dc3545',
            ];
        @endphp

        @foreach($apps as $app)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="app-card h-100 p-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="company-avatar">
                            {{ strtoupper(substr($app->company->name ?? $app->lowongan->perusahaan,0,1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">
                                {{ $app->company->name ?? $app->lowongan->perusahaan }}
                            </div>
                            <div class="text-muted small">
                                Dikirim {{ optional($app->submitted_at)->diffForHumans() }}
                            </div>
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="status-dot" style="background:{{ $dot[$app->status] ?? '#6c757d' }}"></span>
                            <span class="badge text-bg-{{ $badge[$app->status] ?? 'secondary' }}">
                                {{ ucfirst($app->status) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('lowongan.detail', $app->lowongan->slug ?? $app->lowongan_id) }}"
                       class="text-decoration-none text-reset">
                        <div class="mb-2">
                            <div class="fs-6 fw-bold">{{ $app->lowongan->posisi ?? '-' }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>{{ $app->lowongan->lokasi ?? '-' }}
                            </div>
                        </div>
                    </a>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($app->lowongan?->tipe_pekerjaan)
                            <span class="badge rounded-pill text-bg-light border">
                                <i class="bi bi-briefcase me-1"></i>{{ $app->lowongan->tipe_pekerjaan }}
                            </span>
                        @endif
                        @if(!is_null($app->lowongan?->gaji_min) || !is_null($app->lowongan?->gaji_max))
                            <span class="badge rounded-pill text-bg-light border">
                                Rp {{ number_format($app->lowongan->gaji_min ?? 0,0,',','.') }}
                                – Rp {{ number_format($app->lowongan->gaji_max ?? 0,0,',','.') }}
                            </span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-auto">
                        <div>
                            @if($app->cv_path)
                                <a target="_blank" href="{{ asset('storage/'.$app->cv_path) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-paperclip me-1"></i>CV
                                </a>
                            @else
                                <span class="text-muted small">CV: -</span>
                            @endif
                        </div>

                        @if($app->status === 'submitted')
                            <form action="{{ route('user.lamaran.destroy', $app) }}" method="POST"
                                  onsubmit="return confirm('Batalkan lamaran ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle me-1"></i>Batalkan
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">Tidak ada aksi</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $apps->links() }}
    </div>
    @endif
</div>
@endsection
