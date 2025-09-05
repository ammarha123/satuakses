@extends('adminlte::page')

@section('title', 'Edit Lowongan')

@section('content_header')
    <h1>Edit Lowongan</h1>
@endsection

@section('content')
    @if ($errors->any())
      <div class="alert alert-danger">
        <b>Form tidak valid:</b>
        <ul class="mb-0">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('employer.lowongan.update', $lowongan->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Perusahaan</label>
            <input type="text" class="form-control" value="{{ $lowongan->perusahaan }}" disabled>
        </div>

        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="posisi" class="form-control" value="{{ old('posisi', $lowongan->posisi) }}" required>
        </div>

        <div class="mb-3">
            <label>Slug (opsional)</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $lowongan->slug) }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="dekskripsi" class="form-control" rows="4" required>{{ old('dekskripsi', $lowongan->dekskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $lowongan->lokasi) }}" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                @foreach($kategoriLowongans as $kategori)
                    <option value="{{ $kategori->id }}" @selected(old('kategori_id',$lowongan->kategori_id)==$kategori->id)>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipe Pekerjaan</label>
            @php $tpOld = old('tipe_pekerjaan', $lowongan->tipe_pekerjaan); @endphp
            <select name="tipe_pekerjaan" class="form-control">
                @foreach(['Full-time','Part-time','Remote','Hybrid'] as $tp)
                    <option value="{{ $tp }}" @selected($tpOld==$tp)>{{ $tp }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Persyaratan</label>
            <textarea name="persyaratan" class="form-control" rows="3">{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Fasilitas Disabilitas</label>
            <textarea name="fasilitas_disabilitas" class="form-control" rows="2">{{ old('fasilitas_disabilitas', $lowongan->fasilitas_disabilitas) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Gaji Minimum</label>
                <input type="number" name="gaji_min" class="form-control" value="{{ old('gaji_min', $lowongan->gaji_min) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label>Gaji Maksimum</label>
                <input type="number" name="gaji_max" class="form-control" value="{{ old('gaji_max', $lowongan->gaji_max) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label>Kuota</label>
                <input type="number" name="kuota" class="form-control" value="{{ old('kuota', $lowongan->kuota) }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Batas Waktu Lamaran</label>
            <input type="date" name="batas_lamaran" class="form-control" value="{{ old('batas_lamaran', optional($lowongan->batas_lamaran)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            @php $stOld = old('status', $lowongan->status); @endphp
            <select name="status" class="form-control">
                @foreach(['Active','Scheduled','Closed'] as $st)
                    <option value="{{ $st }}" @selected($stOld==$st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Apakah Lowongan Masih Terbuka?</label>
            @php $openOld = old('is_terbuka', (string)($lowongan->is_terbuka ? '1' : '0')); @endphp
            <select name="is_terbuka" class="form-control">
                <option value="1" @selected($openOld==='1')>Ya</option>
                <option value="0" @selected($openOld==='0')>Tidak</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Waktu Posting</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="now" value="now">
                <label class="form-check-label" for="now">Atur Menjadi Sekarang</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="posting_option" id="schedule" value="schedule">
                <label class="form-check-label" for="schedule">Jadwalkan</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="waktu_posting">Tanggal Posting</label>
            <input type="date" name="waktu_posting" id="waktu_posting" class="form-control"
                   value="{{ old('waktu_posting', optional($lowongan->waktu_posting)->format('Y-m-d')) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('employer.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
