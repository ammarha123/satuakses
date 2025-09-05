@extends('adminlte::page')

@section('title', 'Edit Modul')

@section('content_header')
    <h1>
        Edit Modul – {{ $module->course->judul }}</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">Form tidak valid:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.modules.update', $module) }}" method="POST">
        @csrf @method('PUT')
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Judul Modul</label>
                    <input type="text" name="title" class="form-control" required
                        value="{{ old('title', $module->title) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rangkuman</label>
                    <textarea name="summary" class="form-control" rows="3">{{ old('summary', $module->summary) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Video URL (opsional)</label>
                        <input type="url" name="video_url" class="form-control"
                            value="{{ old('video_url', $module->video_url) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" min="1"
                            value="{{ old('sort_order', $module->sort_order) }}">
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('admin.modules.show', $module) }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
    <form action="{{ route('admin.modules.destroy', $module) }}" method="POST"
        onsubmit="return confirm('Hapus modul ini beserta submodulnya?')" class="mb-4">
        @csrf @method('DELETE')
        <button class="btn btn-danger"><i class="fas fa-trash"></i> Hapus Modul</button>
    </form>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Submodul</h5>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalSubmodule">
                <i class="fas fa-plus"></i> Tambah Submodul
            </button>
        </div>
        <div class="card-body p-0">
            @if ($module->submodules->isEmpty())
                <div class="p-3 text-muted">Belum ada submodul.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">Urut</th>
                                <th>Judul</th>
                                <th>Video</th>
                                <th>Lampiran</th>
                                <th style="width:160px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($module->submodules as $sm)
                                <tr>
                                    <td>{{ $sm->sort_order ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold mb-1">{{ $sm->title ?: '(Tanpa judul)' }}</div>
                                        @if ($sm->content)
                                            <div class="small text-muted">{{ Str::limit(strip_tags($sm->content), 90) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if ($sm->video_url)
                                            <a href="{{ $sm->video_url }}" target="_blank">Lihat Video</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if ($sm->attachment_path)
                                            <a href="{{ Storage::disk('public')->url($sm->attachment_path) }}"
                                                target="_blank">Unduh</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.submodules.edit', $sm) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.submodules.destroy', $sm) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus submodul ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="modalSubmodule" tabindex="-1" role="dialog" aria-labelledby="modalSubmoduleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.modules.submodules.store', $module) }}" method="POST"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubmoduleLabel">Tambah Submodul</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul (opsional)</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label>Konten / Materi (opsional)</label>
                        <textarea name="content" class="form-control" rows="5">{{ old('content') }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Video URL (opsional)</label>
                            <input type="url" name="video_url" class="form-control" placeholder="https://..."
                                value="{{ old('video_url') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" min="1"
                                value="{{ old('sort_order', $module->submodules->count() + 1) }}">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label>Lampiran (opsional)</label>
                        <input type="file" name="attachment" class="form-control">
                        <small class="text-muted d-block">Maks 10MB</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

@endsection
