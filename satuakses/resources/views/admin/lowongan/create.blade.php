@extends('adminlte::page')

@section('title', 'Tambah Lowongan')

@section('content_header')
    <h1>Tambah Lowongan</h1>
@endsection

@section('content')
    <form action="{{ route('admin.lowongan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Perusahaan</label>
            <input type="text" name="perusahaan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="posisi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Dekskripsi</label>
            <textarea name="dekskripsi" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kategori (opsional)</label>
            <input type="text" name="kategori" class="form-control">
        </div>
        <div class="mb-3">
            <label>Waktu Posting</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="posting_option" id="now" value="now"
                        checked>
                    <label class="form-check-label" for="now">Posting Sekarang</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="posting_option" id="schedule" value="schedule">
                    <label class="form-check-label" for="schedule">Jadwalkan Posting</label>
                </div>
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
