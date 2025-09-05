@extends('layouts.app')

@section('title', $course->judul . ' | Kursus')

@section('content')

    <div class="py-5" style="background:#E6F7FB;">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2">{{ $course->judul }}</h1>
                    <div class="text-muted mb-3">
                        Kategori: <strong>{{ $course->kategori }}</strong>
                        <span class="mx-2">•</span>
                        Tingkat: <strong>{{ $course->tingkat ?? '-' }}</strong>
                        <span class="mx-2">•</span>
                        Peserta: <strong>{{ $course->enrollments_count }}</strong>
                    </div>
                    <p class="mb-0">{{ $course->deskripsi }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    @auth
                        @role('user')
                            @if (!$sudahEnroll)
                                <form action="{{ route('kursus.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary btn-lg">Ikuti Kursus</button>
                                </form>
                            @else
                                <a href="#modul" class="btn btn-success btn-lg">Lanjutkan Belajar</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk untuk Mengikuti</a>
                        @endrole
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk untuk Mengikuti</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <h5 id="modul" class="fw-bold mb-3">Daftar Modul</h5>

                @forelse ($course->modules as $m)
                    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $m->title }}</div>
                            <div class="text-muted small">{{ $m->summary }}</div>
                        </div>

                        @if ($sudahEnroll)
                            <a class="btn btn-outline-primary btn-sm"
                                href="{{ route('kursus.modules.show', [$course, $m]) }}">
                                Buka Modul
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                Kunci
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="text-muted">Belum ada modul.</div>
                @endforelse

                <h5 class="fw-bold mt-4 mb-3">Quiz</h5>
                @forelse ($course->quizzes as $q)
                    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $q->title }}</div>
                            <div class="text-muted small">Batas waktu: {{ $q->time_limit ? $q->time_limit . ' menit' : '-' }}
                            </div>
                        </div>
                        @if ($sudahEnroll)
                            <a class="btn btn-outline-primary btn-sm"
                                href="{{ route('kursus.quiz.start', [$course, $q]) }}">
                                Mulai Quiz
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>Kunci</button>
                        @endif
                    </div>
                @empty
                    <div class="text-muted">Belum ada quiz.</div>
                @endforelse
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold">Tanggal Mulai</div>
                        <div class="text-muted mb-2">{{ $course->tanggal_mulai?->format('d M Y') ?? '-' }}</div>

                        <div class="fw-semibold">Durasi</div>
                        <div class="text-muted mb-2">{{ $course->durasi ?? '-' }}</div>

                        <div class="fw-semibold">Sertifikat</div>
                        <div class="text-muted mb-2">{{ $course->sertifikat_diberikan ?? true ? 'Ya' : 'Tidak' }}</div>

                        @if ($course->link_pendaftaran)
                            <a class="btn btn-outline-dark w-100" target="_blank" href="{{ $course->link_pendaftaran }}">
                                Info Pendaftaran Eksternal
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
