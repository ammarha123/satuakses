@extends('adminlte::page')

@section('title','Edit Submodul')

@section('content_header')
  <h1>Edit Submodul</h1>
@endsection

@section('content')
@if($errors->any())
  <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('admin.submodules.update', $submodule) }}" method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label">Judul</label>
          <input class="form-control" name="title" value="{{ old('title',$submodule->title) }}">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Sort Order</label>
          <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order',$submodule->sort_order) }}">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Konten</label>
        <textarea class="form-control" name="content" rows="5">{{ old('content',$submodule->content) }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Video URL</label>
        <input class="form-control" name="video_url" value="{{ old('video_url',$submodule->video_url) }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Lampiran (ganti)</label>
        <input type="file" name="attachment" class="form-control">
        @if($submodule->attachment_path)
          <small class="text-muted">Saat ini:
            <a target="_blank" href="{{ Storage::disk('public')->url($submodule->attachment_path) }}">lihat</a>
          </small>
        @endif
      </div>
    </div>
    <div class="card-footer">
      <a href="{{ route('admin.modules.show', $submodule->module) }}" class="btn btn-secondary">Batal</a>
      <button class="btn btn-primary">Simpan</button>
    </div>
  </div>
</form>
@endsection
