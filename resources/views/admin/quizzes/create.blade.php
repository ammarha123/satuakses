@extends('adminlte::page')

@section('title', 'Edit Quiz')

@section('content_header')
  <h1>Edit Quiz – {{ $quiz->course->judul }}</h1>
@endsection

@section('content')
  @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold mb-1">Form tidak valid:</div>
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST" id="quiz-form">
    @csrf @method('PUT')
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8 mb-3">
            <label class="form-label">Judul Quiz</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $quiz->title) }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Batas Waktu (menit)</label>
            <input type="number" name="time_limit" min="1" class="form-control" value="{{ old('time_limit', $quiz->time_limit) }}">
          </div>
        </div>
      </div>
    </div>

    {{-- Soal --}}
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span>Soal</span>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-question">
          <i class="fas fa-plus"></i> Tambah Soal
        </button>
      </div>
      <div class="card-body" id="questions-wrap">
        @php $qidx = 0; @endphp
        @foreach($quiz->questions as $q)
          <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <strong>Soal #{{ $loop->iteration }}</strong>
              <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('.border').remove()">Hapus</button>
            </div>
            <div class="mb-2">
              <label class="form-label">Pertanyaan</label>
              <textarea class="form-control" name="questions[{{ $qidx }}][question]" rows="2" required>{{ $q->question }}</textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Opsi Jawaban</label>
              <div class="row g-2" id="opts-{{ $qidx }}">
                @foreach($q->options as $oi => $opt)
                  <div class="col-12 mb-1">
                    <input class="form-control" name="questions[{{ $qidx }}][options][{{ $oi }}]" value="{{ $opt->text }}" required>
                  </div>
                @endforeach
              </div>
              <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addOption({{ $qidx }})">
                + Tambah Opsi
              </button>
            </div>
            <div class="mb-0">
              <label class="form-label">Index Jawaban Benar (mulai 0)</label>
              <input type="number" class="form-control" name="questions[{{ $qidx }}][correct]"
                     value="{{ $q->correct_index ?? 0 }}" min="0">
            </div>
          </div>
          @php $qidx++; @endphp
        @endforeach
      </div>
      <div class="card-footer">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-secondary">Batal</a>
      </div>
    </div>
  </form>
@endsection

@push('js')
<script>
  const qWrap = document.getElementById('questions-wrap');
  let qIndex = {{ $quiz->questions->count() }};

  document.getElementById('add-question').addEventListener('click', () => {
    qWrap.insertAdjacentHTML('beforeend', questionTemplate(qIndex));
    qIndex++;
  });

  function questionTemplate(qi) {
    return `
      <div class="border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <strong>Soal #${qi+1}</strong>
          <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('.border').remove()">Hapus</button>
        </div>
        <div class="mb-2">
          <label class="form-label">Pertanyaan</label>
          <textarea class="form-control" name="questions[${qi}][question]" rows="2" required></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label">Opsi Jawaban</label>
          <div class="row g-2" id="opts-${qi}">
            ${optionInput(qi,0)}
            ${optionInput(qi,1)}
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addOption(${qi})">
            + Tambah Opsi
          </button>
        </div>
        <div class="mb-0">
          <label class="form-label">Index Jawaban Benar (mulai 0)</label>
          <input type="number" class="form-control" name="questions[${qi}][correct]" value="0" min="0">
        </div>
      </div>
    `;
  }
  function optionInput(qi, oi) {
    return `<div class="col-12 mb-1">
      <input class="form-control" name="questions[${qi}][options][${oi}]" placeholder="Opsi ${oi+1}" required>
    </div>`;
  }
  window.addOption = function(qi){
    const c = document.getElementById('opts-'+qi);
    const oi = c.children.length;
    c.insertAdjacentHTML('beforeend', optionInput(qi, oi));
  }
</script>
@endpush
