{{-- resources/views/admin/quizzes/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Daftar Quiz')

@section('content_header')
    <h1>Daftar Quiz - {{ $course->judul }}</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.courses.quizzes.create', $course) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Quiz Baru
        </a>
        <a href="{{ route('admin.kursus.show', $course) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Kursus
        </a>
    </div>

    @if($quizzes->isEmpty())
        <div class="card">
            <div class="card-body text-center text-muted">
                Belum ada quiz untuk kursus ini.
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($quizzes as $quiz)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $quiz->title }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-clock"></i>
                                {{ $quiz->time_limit ? $quiz->time_limit . ' menit' : 'Tidak ada batas waktu' }}
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-question-circle"></i>
                                {{ $quiz->questions_count }} Soal
                            </p>

                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Hapus quiz ini?')">
                                    @csrf
                                    @method('DELETE')
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
    @endif
@endsection
