@extends('adminlte::page')

@section('title', 'Tambah Lowongan')

@section('content_header')
    <h1>Tambah Lowongan</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">Form tidak valid:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.lowongan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Perusahaan</label>
            <select name="company_id" class="form-control" required>
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($companies as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="posisi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Slug (opsional)</label>
            <input type="text" name="slug" class="form-control">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="dekskripsi" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoriLowongans as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipe Pekerjaan</label>
            <select name="tipe_pekerjaan" class="form-control">
                <option value="Full-time">Full Time</option>
                <option value="Part-time">Part Time</option>
                <option value="Remote">Remote</option>
                <option value="Hybrid">Hybrid</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Persyaratan</label>
            <textarea name="persyaratan" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Fasilitas Disabilitas</label>
            <textarea name="fasilitas_disabilitas" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label>Gaji Minimum</label>
            <input type="number" name="gaji_min" class="form-control">
        </div>

        <div class="mb-3">
            <label>Gaji Maksimum</label>
            <input type="number" name="gaji_max" class="form-control">
        </div>

        <div class="mb-3">
            <label>Kuota</label>
            <input type="number" name="kuota" class="form-control">
        </div>

        <div class="mb-3">
            <label>Batas Waktu Lamaran</label>
            <input type="date" name="batas_lamaran" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Scheduled">Scheduled</option>
                <option value="Closed">Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Apakah Lowongan Masih Terbuka?</label>
            <select name="is_terbuka" class="form-control">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </select>
        </div>

        {{-- Waktu Posting --}}
        <div class="mb-3">
            <label>Waktu Posting</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="now" value="now" checked>
                <label class="form-check-label" for="now">Posting Sekarang</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="schedule" value="schedule">
                <label class="form-check-label" for="schedule">Jadwalkan</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="waktu_posting">Tanggal Posting</label>
            <input type="date" name="waktu_posting" id="waktu_posting" class="form-control" disabled>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nowRadio = document.getElementById('now');
            const scheduleRadio = document.getElementById('schedule');
            const dateInput = document.getElementById('waktu_posting');

            const today = new Date().toISOString().split('T')[0];
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 1);
            const maxDateString = maxDate.toISOString().split('T')[0];

            scheduleRadio.addEventListener('change', function() {
                if (this.checked) {
                    dateInput.disabled = false;
                    dateInput.setAttribute('min', today);
                    dateInput.setAttribute('max', maxDateString);
                }
            });

            nowRadio.addEventListener('change', function() {
                if (this.checked) {
                    dateInput.disabled = true;
                    dateInput.value = '';
                }
            });
        });
    </script>
@endpush
