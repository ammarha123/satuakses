{{-- resources/views/admin/modules/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Daftar Modul')

@section('content_header')
    <h1>Daftar Modul - {{ $course->judul }}</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Modul
        </a>
        <a href="{{ route('admin.kursus.show', $course) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Kursus
        </a>
    </div>

    @if ($modules->isEmpty())
        <div class="card">
            <div class="card-body text-center text-muted">
                Belum ada modul untuk kursus ini.
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($modules as $module)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $module->title }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-layer-group"></i>
                                Urutan: {{ $module->sort_order ?? '-' }}
                            </p>
                            <p class="text-dark small mb-2">
                                <i class="fas fa-book-open me-1"></i> {{ $module->submodules_count }} Submodul
                            </p>
                            @if ($module->summary)
                                <p class="card-text small flex-grow-1">{{ Str::limit($module->summary, 100) }}</p>
                            @else
                                <p class="text-muted small flex-grow-1">Tidak ada ringkasan.</p>
                            @endif

                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.modules.show', $module) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.modules.destroy', $module) }}" method="POST"
                                    onsubmit="return confirm('Hapus modul ini?')">
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
