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
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoriLowongans as $k)
                    <option value="{{ $k->id }}"
                        {{ ($lowongan->kategori_id ?? old('kategori_id')) == $k->id ? 'selected' : '' }}>
                        {{ $k->name ?? ($k->nama ?? 'Kategori') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="posisi" class="form-control" value="{{ old('posisi', $lowongan->posisi) }}"
                required>
        </div>

        <div class="mb-3">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $lowongan->slug) }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $lowongan->deskripsi ?? ($lowongan->dekskripsi ?? '')) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $lowongan->lokasi) }}"
                required>
        </div>

        <div class="mb-3">
            <label>Tipe Pekerjaan</label>
            <input type="text" name="tipe_pekerjaan" class="form-control"
                value="{{ old('tipe_pekerjaan', $lowongan->tipe_pekerjaan) }}">
        </div>

        <div class="mb-3">
            <label>Persyaratan <small class="text-muted">(pisahkan dengan enter untuk list)</small></label>
            <textarea name="persyaratan" class="form-control" rows="2">{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Fasilitas Disabilitas <small class="text-muted">(pisahkan dengan enter untuk list)</small></label>
            <textarea name="fasilitas_disabilitas" class="form-control">{{ old('fasilitas_disabilitas', $lowongan->fasilitas_disabilitas) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Gaji Minimum</label>
                <input type="text" name="gaji_min" class="form-control rupiah"
                    value="{{ $lowongan->gaji_min ? 'Rp ' . number_format($lowongan->gaji_min, 0, ',', '.') : '' }}">
            </div>
            <div class="col-md-4 mb-3">
                <label>Gaji Maksimum</label>
                <input type="text" name="gaji_max" class="form-control rupiah"
                    value="{{ $lowongan->gaji_max ? 'Rp ' . number_format($lowongan->gaji_max, 0, ',', '.') : '' }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Kuota</label>
                <input type="number" name="kuota" class="form-control" value="{{ old('kuota', $lowongan->kuota) }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Batas Lamaran</label>
            <input type="date" name="batas_lamaran" class="form-control"
                value="{{ old('batas_lamaran', optional($lowongan->batas_lamaran) ? \Carbon\Carbon::parse($lowongan->batas_lamaran)->format('Y-m-d') : '') }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active" {{ old('status', $lowongan->status) == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Scheduled" {{ old('status', $lowongan->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled
                </option>
                <option value="Closed" {{ old('status', $lowongan->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Terbuka?</label>
            <select name="is_terbuka" class="form-control">
                <option value="1" {{ old('is_terbuka', $lowongan->is_terbuka) ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !old('is_terbuka', $lowongan->is_terbuka) ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Waktu Posting</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="now" value="now"
                    {{ old('posting_option') == 'now' || (old('posting_option') === null && $lowongan->waktu_posting === null) ? 'checked' : '' }}>
                <label class="form-check-label" for="now">Sekarang</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="schedule" value="schedule"
                    {{ old('posting_option') == 'schedule' || (old('posting_option') === null && $lowongan->waktu_posting !== null) ? 'checked' : '' }}>
                <label class="form-check-label" for="schedule">Jadwalkan</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="waktu_posting">Tanggal Posting</label>
            <input type="date" id="waktu_posting_visible" class="form-control"
                value="{{ old('waktu_posting', optional($lowongan->waktu_posting)->format('Y-m-d')) }}">
            <!-- hidden input with the real name -->
            <input type="hidden" name="waktu_posting" id="waktu_posting"
                value="{{ old('waktu_posting', optional($lowongan->waktu_posting)->format('Y-m-d')) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection

@push('js')
    <script>
        function setPostingToggle() {
            const nowRadio = document.getElementById('now');
            const scheduleRadio = document.getElementById('schedule');
            const visible = document.getElementById('waktu_posting_visible');
            const hidden = document.getElementById('waktu_posting');

            function isoToday() {
                return new Date().toISOString().slice(0, 10);
            }

            function update() {
                if (nowRadio.checked) {
                    visible.readOnly = true;
                    visible.value = isoToday();
                    hidden.value = isoToday();
                } else {
                    visible.readOnly = false;
           
                    hidden.value = visible.value || '';
                }
            }

            visible.addEventListener('input', () => {
                hidden.value = visible.value;
            });

            nowRadio.addEventListener('change', update);
            scheduleRadio.addEventListener('change', update);

            update();
        }

        document.addEventListener('DOMContentLoaded', setPostingToggle);

        document.querySelectorAll('.rupiah').forEach(function(el) {
            el.addEventListener('input', function(e) {
                let value = this.value.replace(/[^0-9]/g, "");
                if (value) {
                    this.value = "Rp " + new Intl.NumberFormat('id-ID').format(value);
                } else {
                    this.value = "";
                }
            });
        });
    </script>
@endpush
