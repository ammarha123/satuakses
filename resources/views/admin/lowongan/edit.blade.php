@extends('adminlte::page')

@section('title', 'Edit Lowongan')

@section('content_header')
    <h1>Edit Lowongan</h1>
@endsection

@section('content')
    <form action="{{ route('admin.lowongan.update', $lowongan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Perusahaan</label>
            <select name="company_id" class="form-control" required>
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($companies as $c)
                    <option value="{{ $c->id }}" @selected(($selectedCompanyId ?? null) == $c->id)>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="posisi" class="form-control" value="{{ $lowongan->posisi }}" required>
        </div>

        <div class="mb-3">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ $lowongan->slug }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="dekskripsi" class="form-control" rows="4" required>{{ $lowongan->dekskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $lowongan->lokasi }}" required>
        </div>

        <div class="mb-3">
            <label>Tipe Pekerjaan</label>
            <input type="text" name="tipe_pekerjaan" class="form-control" value="{{ $lowongan->tipe_pekerjaan }}">
        </div>

        <div class="mb-3">
            <label>Persyaratan</label>
            <textarea name="persyaratan" class="form-control" rows="2">{{ $lowongan->persyaratan }}</textarea>
        </div>

        <div class="mb-3">
            <label>Fasilitas Disabilitas</label>
            <textarea name="fasilitas_disabilitas" class="form-control">{{ $lowongan->fasilitas_disabilitas }}</textarea>
        </div>

        <div class="mb-3">
            <label>Gaji Minimum</label>
            <input type="number" name="gaji_min" class="form-control" value="{{ $lowongan->gaji_min }}">
        </div>

        <div class="mb-3">
            <label>Gaji Maksimum</label>
            <input type="number" name="gaji_max" class="form-control" value="{{ $lowongan->gaji_max }}">
        </div>

        <div class="mb-3">
            <label>Kuota</label>
            <input type="number" name="kuota" class="form-control" value="{{ $lowongan->kuota }}">
        </div>

        <div class="mb-3">
            <label>Batas Lamaran</label>
            <input type="date" name="batas_lamaran" class="form-control" value="{{ $lowongan->batas_lamaran }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active" {{ $lowongan->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Scheduled" {{ $lowongan->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Closed" {{ $lowongan->status == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Terbuka?</label>
            <select name="is_terbuka" class="form-control">
                <option value="1" {{ $lowongan->is_terbuka ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !$lowongan->is_terbuka ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Waktu Posting</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="now" value="now">
                <label class="form-check-label" for="now">Sekarang</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="schedule" value="schedule"
                    checked>
                <label class="form-check-label" for="schedule">Jadwalkan</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="waktu_posting">Tanggal Posting</label>
            <input type="date" name="waktu_posting" id="waktu_posting" class="form-control"
                value="{{ $lowongan->waktu_posting ? $lowongan->waktu_posting->format('Y-m-d') : '' }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
