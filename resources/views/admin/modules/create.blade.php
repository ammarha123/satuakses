@extends('adminlte::page')

@section('title', 'Tambah Modul')

@section('content_header')
  <h1>Tambah Modul – {{ $course->judul }}</h1>
@endsection

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold mb-1">Form tidak valid:</div>
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('admin.courses.modules.store', $course) }}" method="POST">
    @csrf
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Judul Modul</label>
          <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
        </div>
        <div class="mb-3">
          <label class="form-label">Rangkuman</label>
          <textarea name="summary" class="form-control" rows="3">{{ old('summary') }}</textarea>
        </div>
        <div class="row">
          <div class="col-md-8 mb-3">
            <label class="form-label">Video URL (opsional)</label>
            <input type="url" name="video_url" class="form-control" placeholder="https://..." value="{{ old('video_url') }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" min="1" value="{{ old('sort_order', 1) }}">
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.courses.modules.index', $course) }}" class="btn btn-secondary">Batal</a>
      </div>
    </div>
  </form>
@endsection
