{{-- filepath: c:\Users\Ammar\Documents\satuakses\resources\views\admin\quizzes\create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Buat Quiz Baru')

@section('content_header')
  <h1>Buat Quiz Baru – {{ $course->judul }}</h1>
@endsection

@section('content')
  @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold mb-1">Form tidak valid:</div>
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('admin.courses.quizzes.store', $course) }}" method="POST">
    @csrf
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8 mb-3">
            <label class="form-label">Judul Quiz</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Batas Waktu (menit)</label>
            <input type="number" name="time_limit" min="1" class="form-control" value="{{ old('time_limit') }}">
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan dan Masukkan Pertanyaan</button>
      <a href="{{ route('admin.courses.quizzes.index', $course) }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
@endsection

@section('js')
<script>
  let questionIndex = 1;

  // Add new question
  document.getElementById('add-question').addEventListener('click', function () {
    const container = document.getElementById('questions-container');
    const questionTemplate = `
      <div class="question-item mb-4">
        <div class="mb-3">
          <label class="form-label">Pertanyaan</label>
          <input type="text" name="questions[${questionIndex}][question]" class="form-control" placeholder="Masukkan pertanyaan" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Jawaban</label>
          <div class="row">
            ${[0, 1, 2, 3].map(i => `
              <div class="col-md-6 mb-2">
                <input type="text" name="questions[${questionIndex}][options][${i}]" class="form-control" placeholder="Pilihan ${i + 1}" required>
              </div>
            `).join('')}
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Jawaban Benar</label>
          <select name="questions[${questionIndex}][correct]" class="form-select" required>
            <option value="0">Pilihan 1</option>
            <option value="1">Pilihan 2</option>
            <option value="2">Pilihan 3</option>
            <option value="3">Pilihan 4</option>
          </select>
        </div>
        <button type="button" class="btn btn-danger btn-sm remove-question">Hapus Pertanyaan</button>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', questionTemplate);
    questionIndex++;
  });

  // Remove question
  document.getElementById('questions-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-question')) {
      e.target.closest('.question-item').remove();
    }
  });
</script>
@endsection