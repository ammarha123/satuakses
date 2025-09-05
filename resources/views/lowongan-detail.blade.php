@extends('layouts.app')

@section('title', $lowongan->posisi . ' - ' . $lowongan->perusahaan)

@push('styles')
    <style>
        .job-hero {
            background: linear-gradient(135deg, #86d3ff 0%, #6fc0ff 35%, #5aa8ff 100%);
            color: #0b2447;
        }

        .job-hero .muted {
            opacity: .85
        }

        .job-hero .badge-chip {
            background: rgba(255, 255, 255, .9);
            border: 1px solid rgba(0, 0, 0, .05);
            border-radius: 9999px;
            padding: .35rem .75rem;
            font-size: .85rem;
        }

        .job-wrap {
            margin-top: 50px;
        }
    </style>
@endpush

@section('content')

    {{-- HERO --}}
    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <section class="job-hero py-4 py-md-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="{{ route('index') }}" class="text-decoration-none text-dark">
                    <i class="bi bi-arrow-left"></i> <span class="muted">Semua Lowongan</span>
                </a>
                <div class="small muted">
                    Diposting {{ optional($lowongan->waktu_posting ?? $lowongan->created_at)->diffForHumans() }}
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <div class="h5 mb-1">{{ $lowongan->perusahaan }}</div>
                    <div class="muted">{{ $lowongan->lokasi }}</div>
                    <h5> {{ $lowongan->posisi }} </h5>

                    <div class="mt-3 d-flex flex-wrap align-items-center gap-2">
                        <span class="badge-chip">
                            <i class="bi bi-briefcase me-1"></i>{{ $lowongan->tipe_pekerjaan ?? 'Full-Time' }}
                        </span>
                        <span class="badge-chip">
                            Rp {{ number_format($lowongan->gaji_min ?? 0, 0, ',', '.') }}
                            – Rp {{ number_format($lowongan->gaji_max ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @role('user')
                    @if ($hasApplied)
                        <button class="btn btn-secondary px-4" disabled>Sudah dilamar</button>
                    @else
                        <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#applyModal">
                            Lamar
                        </button>
                    @endif
                    @elserole('employer|admin')
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary px-4">Masuk untuk melamar</a>
                @endrole
            </div>
        </div>
    </section>

    <div class="container job-wrap">
        <div class="row g-4">
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold">Deskripsi Pekerjaan</h6>
                        <div class="text-body">{!! nl2br(e($lowongan->dekskripsi)) !!}</div>
                    </div>
                </div>

                @if (!empty($lowongan->persyaratan))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold">Persyaratan</h6>
                            @php $items = preg_split("/\r\n|\n|\r|•|-/", $lowongan->persyaratan); @endphp
                            <ul class="mb-0">
                                @foreach (collect($items)->filter() as $i)
                                    <li>{{ trim($i) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (isset($kursusWajib) && count($kursusWajib))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold">Sertifikat kursus yang dibutuhkan</h6>
                            <ul class="mb-0">
                                @foreach ($kursusWajib as $kursus)
                                    <li class="d-flex align-items-center justify-content-between">
                                        <span>{{ $kursus->judul }}</span>
                                        <a href="{{ route('user.kursus.show', $kursus->id ?? 0) }}"
                                            class="badge text-bg-light border">Ikut Kursus</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif


                @if (!empty($lowongan->fasilitas_disabilitas))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold">Fasilitas Disabilitas</h6>
                            @php $fas = preg_split("/\r\n|\n|\r|•|-/", $lowongan->fasilitas_disabilitas); @endphp
                            <ul class="mb-0">
                                @foreach (collect($fas)->filter() as $f)
                                    <li>{{ trim($f) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif


                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body small">
                        <h6 class="fw-bold">Profil Perusahaan</h6>
                        <p class="mb-0">
                            {{ $lowongan->perusahaan }} adalah perusahaan yang berlokasi di
                            {{ $lowongan->lokasi }}. Kami berkomitmen pada lingkungan kerja inklusif dan
                            mendukung pengembangan karier penyandang disabilitas.
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-lg-4">
                <h6 class="fw-bold mb-2">Rekomendasi Lainnya</h6>
                @forelse($rekomendasi ?? [] as $rec)
                    <a href="{{ route('lowongan.detail', $rec->slug ?? $rec->id) }}" class="text-decoration-none">
                        <div class="border rounded p-3 mb-2 bg-white shadow-sm">
                            <div class="small fw-semibold">{{ $rec->perusahaan }}</div>
                            <div class="small text-muted">{{ $rec->lokasi }}</div>
                            <div class="small">{{ $rec->posisi }}</div>
                            <div class="small text-muted">
                                Rp {{ number_format($rec->gaji_min ?? 0, 0, ',', '.') }}
                                – Rp {{ number_format($rec->gaji_max ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="small text-muted">
                                {{ optional($rec->waktu_posting ?? $rec->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-muted small">Belum ada rekomendasi.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="job-footer border-top bg-white py-2 px-3 d-flex align-items-center justify-content-between"
        style="bottom: 0; z-index: 1000;">
        <div class="small">
            <div class="fw-semibold">{{ $lowongan->posisi }}</div>
            <div class="text-muted">{{ $lowongan->perusahaan }}</div>
        </div>
        @role('user')
            @if ($hasApplied)
                <button class="btn btn-secondary px-4" disabled>Sudah dilamar</button>
            @else
                <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#applyModal">
                    Lamar
                </button>
            @endif
            @elserole('employer|admin')
        @else
            <a href="{{ route('login') }}" class="btn btn-primary px-4">Masuk untuk melamar</a>
        @endrole
    </div>
    @role('user')
        <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('user.apply', $lowongan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="applyModalLabel">Pengajuan Lamaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">CV <span class="text-danger">*</span></label>
                                <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                                <div class="form-text">Format: PDF/DOC/DOCX, maks 2MB.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. HP</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', auth()->user()->no_hp ?? '') }}" placeholder="85xxxxxxxxx">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cover Letter (opsional)</label>
                                <textarea name="cover_letter" rows="4" class="form-control"
                                    placeholder="Perkenalkan dirimu singkat dan alasan melamar">{{ old('cover_letter') }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Lamaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endrole

@endsection
