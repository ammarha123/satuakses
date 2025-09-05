@extends('adminlte::page')

@section('title', 'Detail Quiz')

@section('content_header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Detail Quiz – {{ $quiz->course->judul }}</h1>
    <div>
      <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
      </a>
      <a href="{{ route('admin.courses.quizzes.index', $quiz->course) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>
@endsection

@section('content')
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 mb-2">
          <div class="text-muted small">Judul</div>
          <div class="fw-semibold">{{ $quiz->title }}</div>
        </div>
        <div class="col-md-3 mb-2">
          <div class="text-muted small">Batas Waktu</div>
          <div class="fw-semibold">{{ $quiz->time_limit ? $quiz->time_limit.' menit' : '-' }}</div>
        </div>
        <div class="col-md-3 mb-2">
          <div class="text-muted small">Jumlah Soal</div>
          <div class="fw-semibold">{{ $quiz->questions->count() }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Daftar Soal --}}
  <div class="card">
    <div class="card-header">
      <strong>Soal & Jawaban</strong>
    </div>
    <div class="card-body">
      @forelse($quiz->questions as $idx => $q)
        <div class="mb-4">
          <div class="fw-semibold mb-2">#{{ $idx+1 }}. {{ $q->question }}</div>
          <ol type="A" class="mb-0 ps-3">
            @foreach($q->options as $oi => $opt)
              <li class="{{ $oi == ($q->correct_index ?? -1) ? 'text-success fw-semibold' : '' }}">
                {{ $opt->text }}
                @if($oi == ($q->correct_index ?? -1))
                  <i class="fas fa-check-circle ms-1"></i>
                @endif
              </li>
            @endforeach
          </ol>
        </div>
      @empty
        <div class="text-muted">Belum ada soal.</div>
      @endforelse
    </div>
  </div>

  <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="mt-3"
        onsubmit="return confirm('Hapus quiz ini?')">
    @csrf @method('DELETE')
    <button class="btn btn-danger"><i class="fas fa-trash"></i> Hapus Quiz</button>
  </form>
@endsection
