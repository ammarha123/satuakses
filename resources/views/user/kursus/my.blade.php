@extends('layouts.app')

@section('title', 'Kursus Saya')

@section('content')
    <div class="py-5" style="background:#e8f7fb">
        <div class="container">
            <h1 class="fw-bold mb-1">Kursus Saya</h1>
            <p class="text-muted mb-0">Lihat progres belajar, lanjutkan materi, atau unduh sertifikat yang sudah tersedia.
            </p>
        </div>
    </div>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($courses->isEmpty())
            <div class="alert alert-light border text-center text-muted">
                Kamu belum mengikuti kursus apa pun.
                <a class="ms-1" href="{{ route('kursus.index') }}">Jelajahi kursus</a>.
            </div>
        @else
            <div class="row g-3">
                @foreach ($courses as $c)
                    @php
                        $p = $progress[$c->id] ?? ['percent' => 0, 'done' => 0, 'total' => 0, 'passed' => false];
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm">
                            @if ($c->gambar)
                                <img src="{{ asset('storage/' . $c->gambar) }}" class="card-img-top"
                                    alt="Poster {{ $c->judul }}" style="object-fit:cover;height:160px;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="card-title mb-0">{{ $c->judul }}</h5>
                                    <span class="badge text-bg-secondary">{{ $c->kategori }}</span>
                                </div>
                                <div class="text-muted small mb-2">{{ $c->tingkat ?? 'Umum' }}</div>

                                <div class="small mb-1">Progres: {{ $p['percent'] }}%
                                    ({{ $p['done'] }}/{{ $p['total'] }})</div>
                                <div class="progress mb-2" style="height:8px;">
                                    <div class="progress-bar @if ($p['percent'] == 100) bg-success @endif"
                                        role="progressbar" style="width: {{ $p['percent'] }}%;"
                                        aria-valuenow="{{ $p['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('kursus.show', $c->slug) }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-play-circle"></i> Lanjutkan
                                    </a>
                                    @if ($p['percent'] === 100 && $p['passed'])
                                        <a href="{{ route('kursus.certificate.download', $c->slug) }}"
                                            class="btn btn-success" title="Unduh sertifikat">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    @if ((int) $p['percent'] === 100)
                                        @if ($c->quizzes_count > 0)
                                            @if ($p['passed'])
                                                <span class="badge text-bg-success"><i class="bi bi-patch-check-fill"></i>
                                                    Lulus</span>
                                            @else
                                                <span class="badge text-bg-warning text-dark">
                                                    <i class="bi bi-exclamation-circle"></i> Nilai kuis belum cukup
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge text-bg-success"><i class="bi bi-patch-check-fill"></i>
                                                Selesai</span>
                                        @endif
                                    @else
                                        <span class="badge text-bg-secondary">Belum selesai</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
@endsection
