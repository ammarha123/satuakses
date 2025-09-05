@extends('layouts.app')

@section('title', 'Daftar Kursus')

@section('content')
    <div class="py-5" style="background:#e8f7fb">
        <div class="container">
            <h1 class="fw-bold mb-3">Kursus</h1>
            <p class="text-muted mb-0">Jelajahi kursus yang tersedia. Cari berdasarkan judul, kategori, atau tingkat.</p>
        </div>
    </div>

    <div class="container py-4">
        <form method="GET" class="card shadow-sm mb-4">
            <div class="card-body row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="q" value="{{ $q }}" class="form-control"
                        placeholder="Cari judul atau deskripsi…">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tingkat</label>
                    <select name="tingkat" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($levels as $lvl)
                            <option value="{{ $lvl }}" {{ $tingkat === $lvl ? 'selected' : '' }}>{{ $lvl }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary"><i class="bi bi-search"></i> Terapkan</button>
                    @if (request()->hasAny(['q', 'kategori', 'tingkat']) && (strlen($q) || $kategori || $tingkat))
                        <a href="{{ route('kursus.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
        @if ($courses->count() === 0)
            <div class="alert alert-light border text-center text-muted">Tidak ada kursus yang cocok.</div>
        @else
            <div class="row g-3">
                @foreach ($courses as $c)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 shadow-sm">
                            @if ($c->gambar)
                                <img src="{{ asset('storage/' . $c->gambar) }}" class="card-img-top"
                                    alt="Poster {{ $c->judul }}" style="object-fit:cover;height:170px;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title mb-1">{{ $c->judul }}</h5>
                                    <span class="badge rounded-pill text-bg-secondary">{{ $c->kategori }}</span>
                                </div>
                                <div class="text-muted small mb-2">{{ $c->tingkat ?? 'Umum' }}</div>
                                <p class="card-text small flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($c->deskripsi), 150) }}
                                </p>

                                <div class="d-flex gap-2 my-2 small">
                                    <span class="badge rounded-pill text-bg-light">
                                        <i class="bi bi-people"></i> {{ $c->enrollments_count }} peserta
                                    </span>
                                    <span class="badge rounded-pill text-bg-light">
                                        <i class="bi bi-layers"></i> {{ $c->modules_count }} modul
                                    </span>
                                    <span class="badge rounded-pill text-bg-light">
                                        <i class="bi bi-question-circle"></i> {{ $c->quizzes_count }} kuis
                                    </span>
                                </div>

                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('kursus.show', $c->slug) }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
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
