@extends('layouts.app')

@section('title', $course->judul . ' | Kursus')

@section('content')
    @php
        // status ikut
        $isEnrolled =
            $isEnrolled ??
            (auth()->check() && method_exists($course, 'users') && $course->relationLoaded('users')
                ? $course->users->contains('id', auth()->id())
                : false);

        $enrolledCount = $course->enrollments_count ?? ($course->enrollments->count() ?? 0);
    @endphp
    <div class="py-5" style="background:#e8f7fb">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">{{ $course->judul }}</h1>
                    <div class="text-muted mb-3">
                        <span class="me-3"><i class="bi bi-grid"></i> {{ $course->kategori }}</span>
                        <span class="me-3"><i class="bi bi-graph-up"></i> {{ $course->tingkat ?? 'Umum' }}</span>
                        @if ($course->tanggal_mulai)
                            <span class="me-3"><i class="bi bi-calendar-event"></i> Mulai
                                {{ \Carbon\Carbon::parse($course->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                        @endif
                        @if ($course->durasi)
                            <span class="me-3"><i class="bi bi-hourglass"></i> Durasi {{ $course->durasi }}</span>
                        @endif
                        <span class="me-3"><i class="bi bi-people"></i> {{ $enrolledCount }} peserta</span>
                        @if ($course->sertifikat_diberikan ?? false)
                            <span class="badge text-bg-success"><i class="bi bi-patch-check-fill"></i> Sertifikat</span>
                        @endif
                    </div>

                    @auth
                        @if (auth()->user()->hasRole('user'))
                            <form action="{{ route('kursus.enroll', $course->slug) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-primary px-4" {{ $isEnrolled ? 'disabled' : '' }}>
                                    @if ($isEnrolled)
                                        <i class="bi bi-check2-circle"></i> Sudah Diikuti
                                    @else
                                        <i class="bi bi-plus-circle"></i> Ikuti Kursus
                                    @endif
                                </button>
                            </form>
                            @if ($isEnrolled)
                                <a href="#modules" class="btn btn-outline-dark ms-2 px-4">
                                    <i class="bi bi-play-circle"></i> Mulai Belajar
                                </a>
                            @endif
                            @php
                                $eligible = false;
                                if ($isEnrolled) {
                                    // syarat 1: semua submodul pernah dibuka
                                    $totalSub = $course->modules->flatMap->submodules->count();
                                    $viewed = isset($progressMap) ? $progressMap->count() : 0;
                                    $allSubOpened = $totalSub > 0 ? $viewed >= $totalSub : true;

                                    // syarat 2: semua kuis lulus (jika ada)
                                    $allQuizPassed = true;
                                    if (($course->quizzes ?? collect())->count()) {
                                        foreach ($course->quizzes as $qz) {
                                            $lastAttempt = auth()->check()
                                                ? $qz
                                                    ->attempts()
                                                    ->where('user_id', auth()->id())
                                                    ->latest('id')
                                                    ->first()
                                                : null;
                                            if (!$lastAttempt || ($lastAttempt->score ?? 0) < 75) {
                                                $allQuizPassed = false;
                                                break;
                                            }
                                        }
                                    }
                                    $eligible = $allSubOpened && $allQuizPassed;
                                }
                            @endphp

                            @if ($eligible)
                                <div class="alert alert-success d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <i class="bi bi-patch-check-fill"></i>
                                        Selamat! Kamu telah menyelesaikan semua materi & lulus kuis.
                                    </div>
                                    <a href="{{ route('kursus.certificate.download', $course->slug) }}" class="btn btn-success">
                                        <i class="bi bi-download"></i> Unduh Sertifikat
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary px-4">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk untuk Ikuti
                        </a>
                    @endguest
                </div>

                @if ($course->gambar)
                    <div class="col-md-4 d-none d-md-block">
                        <img src="{{ asset('storage/' . $course->gambar) }}" class="img-fluid rounded shadow"
                            alt="Poster Kursus">
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Tentang Kursus</h5>
                        <div class="text-body">{!! nl2br(e($course->deskripsi)) !!}</div>

                        @if ($course->link_pendaftaran)
                            <a href="{{ $course->link_pendaftaran }}" target="_blank" rel="noopener"
                                class="btn btn-outline-primary mt-3">
                                <i class="bi bi-box-arrow-up-right"></i> Info / Pendaftaran Eksternal
                            </a>
                        @endif
                    </div>
                </div>
                <div id="modules" class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Modul Kursus</h5>

                        @if (($course->modules ?? collect())->count())
                            @foreach ($course->modules as $m)
                                <div class="mb-3 border rounded">
                                    <div class="p-3 d-flex align-items-center justify-content-between bg-light">
                                        <div class="fw-semibold">{{ $m->title }}</div>
                                        <span class="badge text-bg-secondary">{{ $m->submodules->count() }} Submodul</span>
                                    </div>

                                    @if ($m->summary)
                                        <div class="px-3 pt-2 text-muted small">{{ $m->summary }}</div>
                                    @endif

                                    <div class="table-responsive p-3 pt-2">
                                        @if ($m->submodules->count())
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width:60px">Urut</th>
                                                        <th>Submodul</th>
                                                        <th style="width:140px">Status</th>
                                                        <th style="width:120px">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($m->submodules as $s)
                                                        @php
                                                            $opened = isset($progressMap[$s->id]);
                                                            $isActive =
                                                                isset($selectedSubmodule) &&
                                                                $selectedSubmodule &&
                                                                $selectedSubmodule->id === $s->id;
                                                        @endphp
                                                        <tr @class(['table-primary' => $isActive])>
                                                            <td>{{ $s->sort_order ?? '-' }}</td>
                                                            <td>
                                                                <div class="fw-semibold">{{ $s->title ?: 'Submodul' }}
                                                                </div>
                                                                @if ($s->content)
                                                                    <div class="small text-muted">
                                                                        {{ \Illuminate\Support\Str::limit(strip_tags($s->content), 100) }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($opened)
                                                                    <span class="badge text-bg-success"><i
                                                                            class="bi bi-check-circle"></i> Sudah
                                                                        dibuka</span>
                                                                @else
                                                                    <span class="badge text-bg-secondary">Belum
                                                                        dibuka</span>
                                                                @endif
                                                            </td>
                                                            <td>

                                                                @if ($isEnrolled)
                                                                    <a target="_blank"
                                                                        href="{{ route('kursus.submodules.show', [$course->slug, $m->id, $s->id]) }}"
                                                                        class="btn btn-outline-primary btn-sm">
                                                                        <i class="bi bi-journal-bookmark"></i> Buka
                                                                    </a>
                                                                @else
                                                                    <button class="btn btn-outline-secondary btn-sm"
                                                                        disabled>Buka</button>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="text-muted small p-3">Belum ada submodul pada modul ini.</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted">Belum ada modul.</div>
                        @endif
                    </div>
                </div>
                @if (isset($selectedModule) && isset($selectedSubmodule))
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Materi: {{ $selectedSubmodule->title ?: 'Submodul' }}</h5>
                                <a href="{{ route('kursus.show', $course->slug) }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-x-circle"></i> Tutup Materi
                                </a>
                            </div>
                            <hr>

                            @if ($selectedSubmodule->video_url)
                                <div class="ratio ratio-16x9 mb-3">
                                    <iframe src="{{ $selectedSubmodule->video_url }}" allowfullscreen></iframe>
                                </div>
                            @endif

                            @if ($selectedSubmodule->attachment_path ?? false)
                                <div class="mb-3">
                                    <a class="btn btn-outline-secondary btn-sm"
                                        href="{{ Storage::disk('public')->url($selectedSubmodule->attachment_path) }}"
                                        target="_blank">
                                        <i class="bi bi-paperclip"></i> Unduh Lampiran
                                    </a>
                                </div>
                            @endif

                            <div class="text-body">{!! nl2br(e($selectedSubmodule->content)) !!}</div>
                        </div>
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Kuis</h5>
                        @if (($course->quizzes ?? collect())->count())
                            @foreach ($course->quizzes as $qz)
                                @php
                                    $lastAttempt = auth()->check()
                                        ? $qz
                                            ->attempts()
                                            ->where('user_id', auth()->id())
                                            ->latest('id')
                                            ->first()
                                        : null;
                                    $lastScore = $lastAttempt->score ?? null;
                                @endphp

                                <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $qz->title }}</div>
                                        @if (!is_null($qz->time_limit))
                                            <div class="text-muted small"><i class="bi bi-stopwatch"></i> Batas waktu
                                                {{ $qz->time_limit }} menit</div>
                                        @endif

                                        @if (!is_null($lastScore))
                                            <div class="mt-1 small">
                                                Nilai terakhir: <strong>{{ $lastScore }}</strong>
                                                @if ($lastScore < 75)
                                                    <span class="badge text-bg-warning ms-2">Belum cukup</span>
                                                @else
                                                    <span class="badge text-bg-success ms-2">Selesai</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    @if ($isEnrolled)
                                        @if ($allViewed)
                                            @if (is_null($lastScore) || $lastScore < 75)
                                                <a href="{{ route('kursus.quiz.start', [$course->slug, $qz->id]) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    Ambil @if (!is_null($lastScore))
                                                        Ulang
                                                    @endif Kuis
                                                </a>
                                            @else
                                                <a href="{{ route('kursus.quiz.start', [$course->slug, $qz->id]) }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    Kerjakan Lagi (opsional)
                                                </a>
                                            @endif
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled
                                                title="Selesaikan membuka semua submodul dulu">
                                                <i class="bi bi-lock"></i> Mulai Kuis
                                            </button>
                                        @endif
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Mulai Kuis</button>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted">Belum ada kuis.</div>
                        @endif
                    </div>
                </div>


            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold">Ringkasan</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-1"><i class="bi bi-check2-circle"></i> Status:
                                {{ $course->status ?? 'Active' }}</li>
                            @if ($course->kuota)
                                <li class="mb-1"><i class="bi bi-people"></i> Kuota: {{ $course->kuota }}</li>
                            @endif
                            @if ($course->sertifikat_diberikan ?? false)
                                <li class="mb-1"><i class="bi bi-patch-check-fill"></i> Sertifikat disediakan</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @auth
                            @if (auth()->user()->hasRole('user'))
                                <form action="{{ route('kursus.enroll', $course->slug) }}" method="POST"
                                    class="d-grid gap-2">
                                    @csrf
                                    <button class="btn btn-primary" {{ $isEnrolled ? 'disabled' : '' }}>
                                        @if ($isEnrolled)
                                            <i class="bi bi-check2-circle"></i> Sudah Diikuti
                                        @else
                                            <i class="bi bi-plus-circle"></i> Ikuti Kursus
                                        @endif
                                    </button>
                                    @if ($isEnrolled)
                                        <a href="#modules" class="btn btn-outline-dark">
                                            <i class="bi bi-play-circle"></i> Lanjutkan Belajar
                                        </a>
                                    @endif
                                </form>
                            @endif
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk untuk Ikuti
                            </a>
                        @endguest>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
