@extends('adminlte::page')

@section('title', 'Detail Modul')

@section('content_header')
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Detail Modul</h1>
    <div>
      <a href="{{ route('admin.courses.modules.index', $module->course) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit Modul
      </a>
    </div>
  </div>
@endsection

@section('content')
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card mb-3">
    <div class="card-body">
      <h4 class="mb-1">{{ $module->title }}</h4>
      <div class="text-muted small mb-2">
        Urutan: <strong>{{ $module->sort_order ?? '-' }}</strong>
        @if($module->video_url)
          &nbsp;&nbsp;|&nbsp;&nbsp;<i class="fas fa-video"></i>
          <a href="{{ $module->video_url }}" target="_blank">Video</a>
        @endif
      </div>
      @if($module->summary)
        <p class="mb-0">{{ $module->summary }}</p>
      @else
        <p class="text-muted mb-0">Tidak ada ringkasan.</p>
      @endif
    </div>
  </div>

  <div class="row">
    <div class="col-lg-7">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Submodul</span>
        </div>
        <div class="card-body">
          @if($module->submodules->isEmpty())
            <div class="text-muted">Belum ada submodul.</div>
          @else
            @foreach($module->submodules->sortBy('sort_order') as $sub)
              <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-semibold">{{ $sub->title ?: 'Tanpa Judul' }}</div>
                    <div class="small text-muted mb-2">
                      Urutan: {{ $sub->sort_order ?? '-' }}
                    </div>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.submodules.edit', $sub) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.submodules.destroy', $sub) }}" method="POST"
                          onsubmit="return confirm('Hapus submodul ini?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                  </div>
                </div>

                @if($sub->content)
                  <div class="small mt-2">{!! nl2br(e($sub->content)) !!}</div>
                @endif

                <div class="small mt-2">
                  @if($sub->video_url)
                    <div><i class="fas fa-video me-1"></i> Video: <a href="{{ $sub->video_url }}" target="_blank">{{ $sub->video_url }}</a></div>
                  @endif
                  @if($sub->attachment_path)
                    <div><i class="fas fa-paperclip me-1"></i> Lampiran:
                      <a href="{{ Storage::disk('public')->url($sub->attachment_path) }}" target="_blank">Unduh</a>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-header">Tambah Submodul</div>
        <form action="{{ route('admin.modules.submodules.store', $module) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Judul (opsional)</label>
              <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Konten / Materi (opsional)</label>
              <textarea name="content" class="form-control" rows="4">{{ old('content') }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Video URL (opsional)</label>
              <input type="url" name="video_url" class="form-control" placeholder="https://..." value="{{ old('video_url') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Lampiran (opsional)</label>
              <input type="file" name="attachment" class="form-control">
              <small class="text-muted d-block">Maks 10MB</small>
            </div>
            <div class="mb-0">
              <label class="form-label">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" min="1" value="{{ old('sort_order') }}">
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-plus"></i> Tambahkan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
