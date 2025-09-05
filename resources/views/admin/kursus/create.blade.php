@extends('adminlte::page')

@section('title', 'Tambah Kursus')

@section('content_header')
  <h1>Tambah Kursus</h1>
@endsection

@section('content')
@if ($errors->any())
  <div class="alert alert-danger">
    <div class="fw-bold mb-1">Form tidak valid:</div>
    <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form action="{{ route('admin.kursus.store') }}" method="POST" id="course-form" enctype="multipart/form-data">
  @csrf

  <div class="row">
    <div class="col-lg-7">
      <div class="card mb-3">
        <div class="card-header">Informasi Kursus</div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" required value="{{ old('judul') }}">
          </div>

          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label">Slug (opsional)</label>
              <input type="text" name="slug" class="form-control" placeholder="otomatis jika kosong" value="{{ old('slug') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-control">
                <option value="Active"   {{ old('status')=='Active'?'selected':'' }}>Active</option>
                <option value="Draft"    {{ old('status')=='Draft'?'selected':'' }}>Draft</option>
                <option value="Inactive" {{ old('status')=='Inactive'?'selected':'' }}>Inactive</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="5" required>{{ old('deskripsi') }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Kategori</label>
              <input type="text" name="kategori" class="form-control" required value="{{ old('kategori') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tingkat</label>
              <input type="text" name="tingkat" class="form-control" value="{{ old('tingkat') }}">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Durasi (mis. 4 minggu)</label>
              <input type="text" name="durasi" class="form-control" value="{{ old('durasi') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Kuota (opsional)</label>
              <input type="number" name="kuota" class="form-control" min="0" value="{{ old('kuota') }}">
            </div>
          </div>

          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label">Link Pendaftaran (opsional)</label>
              <input type="url" name="link_pendaftaran" class="form-control" placeholder="https://..." value="{{ old('link_pendaftaran') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label d-block">Sertifikat Diberikan?</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="sertifikat" name="sertifikat_diberikan" value="1"
                       {{ old('sertifikat_diberikan', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="sertifikat">Ya</label>
              </div>
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label">Gambar Poster (opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="form-control">
            <small class="text-muted">Maks 2 MB (jpg/png/webp)</small>
          </div>
        </div>
      </div>

      {{-- QUIZ --}}
      <div class="card mb-3">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Quiz (opsional)</span>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="quiz-enable">
            <label class="form-check-label" for="quiz-enable">Aktifkan</label>
          </div>
        </div>
        <div class="card-body" id="quiz-section" style="display:none">
          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label">Judul Quiz</label>
              <input type="text" name="quiz[title]" class="form-control" placeholder="Mis. Post-test">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Batas Waktu (menit)</label>
              <input type="number" name="quiz[time_limit]" class="form-control" min="1">
            </div>
          </div>

          <div class="border rounded p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="mb-0">Pertanyaan</h6>
              <button type="button" class="btn btn-sm btn-outline-primary" id="add-question">
                <i class="fas fa-plus"></i> Tambah Soal
              </button>
            </div>
            <div id="questions-wrap"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- MODUL --}}
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Modul Kursus</span>
          <button type="button" class="btn btn-sm btn-outline-primary" id="add-module">
            <i class="fas fa-plus"></i> Tambah Modul
          </button>
        </div>
        <div class="card-body">
          <div id="modules-wrap"><!-- modul dinamis --></div>
          <div class="text-muted small">Urutan modul mengikuti <em>Sort Order</em>.</div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <button class="btn btn-primary mb-3"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary mb-3">Batal</a>
  </div>
</form>
@endsection

@push('js')
<script>
  // Toggle quiz
  const quizEnable = document.getElementById('quiz-enable');
  const quizSec = document.getElementById('quiz-section');
  quizEnable.addEventListener('change', () => {
    quizSec.style.display = quizEnable.checked ? 'block' : 'none';
    if (!quizEnable.checked) {
      document.getElementById('questions-wrap').innerHTML = '';
      document.querySelector('input[name="quiz[title]"]').value = '';
      document.querySelector('input[name="quiz[time_limit]"]').value = '';
    }
  });
  const modulesWrap = document.getElementById('modules-wrap');
  document.getElementById('add-module').addEventListener('click', () => {
    const idx = modulesWrap.children.length;
    modulesWrap.insertAdjacentHTML('beforeend', moduleTemplate(idx));
  });

  function moduleTemplate(idx){
    return `
      <div class="border rounded p-3 mb-3 module-card">
        <div class="d-flex justify-content-between align-items-center">
          <strong>Modul #${idx+1}</strong>
          <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('.module-card').remove()">Hapus</button>
        </div>

        <div class="mt-2">
          <label class="form-label">Judul</label>
          <input class="form-control" name="modules[${idx}][title]" required>
        </div>
        <div class="mt-2">
          <label class="form-label">Rangkuman</label>
          <textarea class="form-control" name="modules[${idx}][summary]" rows="2"></textarea>
        </div>
        <div class="mt-2">
          <label class="form-label">Video URL (opsional)</label>
          <input class="form-control" name="modules[${idx}][video_url]" placeholder="https://...">
        </div>
        <div class="mt-2">
          <label class="form-label">Sort Order</label>
          <input type="number" class="form-control" name="modules[${idx}][sort_order]" min="1" value="${idx+1}">
        </div>

        <hr class="my-3">
        <div class="d-flex justify-content-between align-items-center">
          <strong>Submodul</strong>
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addSubmodule(${idx})">
            <i class="fas fa-plus"></i> Tambah Submodul
          </button>
        </div>
        <div class="mt-2" id="submodules-wrap-${idx}"></div>
      </div>
    `;
  }

  function addSubmodule(mIdx){
    const wrap = document.getElementById(`submodules-wrap-${mIdx}`);
    const sIdx = wrap.children.length;
    wrap.insertAdjacentHTML('beforeend', submoduleTemplate(mIdx, sIdx));
  }

  function submoduleTemplate(mIdx, sIdx){
    return `
      <div class="border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <span><strong>Submodul #${sIdx+1}</strong></span>
          <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('.border').remove()">Hapus</button>
        </div>

        <div class="mt-2">
          <label class="form-label">Judul Materi</label>
          <input class="form-control" name="modules[${mIdx}][submodules][${sIdx}][title]">
        </div>
        <div class="mt-2">
          <label class="form-label">Konten / Materi (teks)</label>
          <textarea class="form-control" rows="3" name="modules[${mIdx}][submodules][${sIdx}][content]"></textarea>
        </div>
        <div class="mt-2">
          <label class="form-label">Video URL (opsional)</label>
          <input class="form-control" name="modules[${mIdx}][submodules][${sIdx}][video_url]" placeholder="https://...">
        </div>
        <div class="mt-2">
          <label class="form-label">Lampiran (opsional)</label>
          <input type="file" class="form-control" name="modules[${mIdx}][submodules][${sIdx}][attachment]">
          <small class="text-muted">pdf/docx/zip, maks 10MB</small>
        </div>
        <div class="mt-2">
          <label class="form-label">Sort Order</label>
          <input type="number" class="form-control" name="modules[${mIdx}][submodules][${sIdx}][sort_order]" min="1" value="${sIdx+1}">
        </div>
      </div>
    `;
  }

  // Quiz questions
  const qWrap = document.getElementById('questions-wrap');
  document.getElementById('add-question').addEventListener('click', () => {
    const qi = qWrap.children.length;
    qWrap.insertAdjacentHTML('beforeend', questionTemplate(qi));
  });

  function questionTemplate(qi) {
    return `
      <div class="border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <strong>Soal #${qi+1}</strong>
          <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('.border').remove()">Hapus</button>
        </div>
        <div class="mt-2">
          <label class="form-label">Pertanyaan</label>
          <textarea class="form-control" name="quiz[questions][${qi}][question]" rows="2" required></textarea>
        </div>
        <div class="mt-2">
          <label class="form-label">Opsi Jawaban</label>
          <div class="row g-2" id="opts-${qi}">
            ${optionInput(qi,0)}
            ${optionInput(qi,1)}
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addOption(${qi})">
            + Tambah Opsi
          </button>
        </div>
        <div class="mt-2">
          <label class="form-label">Index Jawaban Benar (mulai 0)</label>
          <input type="number" class="form-control" name="quiz[questions][${qi}][correct]" value="0" min="0">
        </div>
      </div>
    `;
  }
  function optionInput(qi, oi) {
    return `<div class="col-12"><input class="form-control" name="quiz[questions][${qi}][options][${oi}]" placeholder="Opsi ${oi+1}" required></div>`;
  }
  function addOption(qi){
    const c = document.getElementById('opts-'+qi);
    const oi = c.children.length;
    c.insertAdjacentHTML('beforeend', optionInput(qi, oi));
  }
</script>
@endpush
