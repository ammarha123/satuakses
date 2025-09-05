@extends('adminlte::page')

@section('title', 'Kelola Kursus')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Kelola Kursus</h1>
        <a href="{{ route('admin.kursus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kursus
        </a>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($kursus->count() === 0)
        <div class="card">
            <div class="card-body text-center text-muted">
                Belum ada kursus. Klik <strong>Tambah Kursus</strong> untuk membuat yang pertama.
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($kursus as $k)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between">
                                <h5 class="card-title mb-1">{{ $k->judul }}</h5>
                                <span class="badge bg-secondary">{{ $k->kategori }}</span>
                            </div>
                            <div class="text-muted small mb-2">{{ $k->tingkat ?? 'Umum' }}</div>
                            <p class="card-text flex-grow-1 text-truncate"
                                style="-webkit-line-clamp:3; display:-webkit-box; -webkit-box-orient:vertical;">
                                {{ strip_tags(Str::limit($k->deskripsi, 160)) }}
                            </p>

                            <div class="d-flex gap-2 my-2">
                                <span class="badge rounded-pill bg-light text-dark">
                                    <i class="fas fa-user-graduate me-1"></i> {{ $k->enrollments_count }} Peserta
                                </span>
                                <span class="badge rounded-pill bg-light text-dark">
                                    <i class="fas fa-layer-group me-1"></i> {{ $k->modules_count }} Modul
                                </span>
                                <span class="badge rounded-pill bg-light text-dark">
                                    <i class="fas fa-question-circle me-1"></i> {{ $k->quizzes_count }} Quiz
                                </span>
                            </div>

                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.kursus.show', $k) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.kursus.edit', $k) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <a href="{{ route('admin.courses.modules.index', $k) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-layer-group"></i> Modul
                                </a>
                                <a href="{{ route('admin.courses.quizzes.index', $k) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-question-circle"></i> Quiz
                                </a>

                                <form action="{{ route('admin.kursus.destroy', $k) }}" method="POST" class="ms-auto"
                                    onsubmit="return confirm('Hapus kursus ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $kursus->links() }}
        </div>
    @endif
@endsection
