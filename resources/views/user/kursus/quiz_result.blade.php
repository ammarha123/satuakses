@extends('layouts.app')

@section('title', 'Hasil Quiz: '.$quiz->title)

@section('content')
<div class="container py-5" style="max-width:820px">
  <div class="text-center mb-3">
    <a href="{{ route('kursus.show', $course->slug) }}" class="text-decoration-none">← Kembali ke Kursus</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body text-center p-5">
      <h3 class="fw-bold mb-2">Hasil Kuis</h3>
      <div class="mb-4 text-muted">{{ $quiz->title }}</div>

      <div class="display-5 fw-bold {{ $passed ? 'text-success' : 'text-danger' }}">{{ $score }}</div>
      <div class="mb-3">Skor kamu</div>

      @if(isset($total, $correct))
        <div class="mb-4 small text-muted">{{ $correct }} benar dari {{ $total }} soal</div>
      @endif

      @if($passed)
        <div class="alert alert-success">Selamat! Kamu lulus kuis ini.</div>
        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('kursus.show', $course->slug) }}" class="btn btn-primary">Kembali ke Kursus</a>
          <a href="{{ route('kursus.quiz.start', [$course->slug, $quiz->id]) }}" class="btn btn-outline-secondary">
            Ambil Ulang (opsional)
          </a>
        </div>
      @else
        <div class="alert alert-warning">Nilai belum cukup (min 75). Silakan ambil ulang kuis.</div>
        <a href="{{ route('kursus.quiz.start', [$course->slug, $quiz->id]) }}" class="btn btn-warning">
          Ambil Ulang Kuis
        </a>
      @endif
    </div>
  </div>
</div>
@endsection
