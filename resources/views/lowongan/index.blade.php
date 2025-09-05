@extends('layouts.app')

@section('title', 'Lowongan Pekerjaan')

@push('styles')
    <style>
        .hero-jobs {
            background: linear-gradient(135deg, #86d3ff 0%, #6fc0ff 35%, #5aa8ff 100%);
        }

        .hero-illustration {
            max-width: 220px;
        }

        .job-card:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
        }

        .filter-box {
            background: #fff;
            border: 1px solid #E7E7E7;
            border-radius: .5rem
        }

        .chip {
            border-radius: 9999px;
            background: #f6f7fb;
            border: 1px solid #e9ebf2;
            font-size: .75rem;
            padding: .15rem .5rem
        }
    </style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="hero-jobs text-dark pt-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2" style="line-height:1.1">
                        Temukan Pekerjaan <br>Ramah Disabilitas
                    </h1>
                    <p class="mb-0">
                        Temukan pelatihan yang dirancang khusus untuk kamu, <br>
                        dan dapatkan pekerjaan yang sesuai dengan potensimu.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    {{-- pakai ilustrasi sendiri bila punya --}}
                    <img class="hero-illustration img-fluid" src="{{ asset('img/hero-people.svg') }}" alt="">
                </div>
            </div>

            {{-- Search bar --}}
            <form class="bg-white shadow-sm rounded-3 p-3 mt-5 floating-search" method="GET"
                action="{{ route('lowongan.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <label class="small text-muted">Lokasi</label>
                        <input type="text" name="lokasi" value="{{ request('lokasi') }}" class="form-control"
                            placeholder="Kota / Provinsi">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted">Bidang Pekerjaan</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                            placeholder="Contoh: Administrasi">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Jenis Disabilitas</label>
                        <select name="disability" class="form-select">
                            <option value="">Semua</option>
                            <option value="tuli" @selected(request('disability') == 'tuli')>Tuli</option>
                            <option value="netra" @selected(request('disability') == 'netra')>Netra</option>
                            <option value="daksa" @selected(request('disability') == 'daksa')>Daksa</option>
                            <option value="rungu" @selected(request('disability') == 'rungu')>Rungu</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button class="btn btn-primary mt-4 mt-md-0">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="container py-4">

        <div class="row g-4">

            <aside class="col-lg-3">
                <h6 class="fw-bold mb-3">Daftar Pekerjaan</h6>

                <div class="filter-box p-3 mb-3">
                    <div class="fw-semibold mb-2">Urut Berdasarkan</div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort1" value="relevan"
                            form="sideFilter" @checked(request('sort', 'relevan') == 'relevan')>
                        <label class="form-check-label" for="sort1">Paling Relevan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort2" value="baru"
                            form="sideFilter" @checked(request('sort') == 'baru')>
                        <label class="form-check-label" for="sort2">Baru Ditambahkan</label>
                    </div>
                </div>

                <div class="filter-box p-3 mb-3">
                    <div class="fw-semibold mb-2">Tingkat Pendidikan</div>
                    @php $ed = (array) request('edu',[]); @endphp
                    @foreach (['SLB', 'SMA', 'SMK', 'S1 atau lebih' => 'S1+'] as $k => $label)
                        @php
                            $val = is_numeric($k) ? $label : $k;
                            $text = is_numeric($k) ? $label : $k;
                        @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $val }}"
                                id="edu{{ $loop->index }}" name="edu[]" form="sideFilter" @checked(in_array($val, $ed))>
                            <label class="form-check-label" for="edu{{ $loop->index }}">{{ $text }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="filter-box p-3">
                    <div class="fw-semibold mb-2">Fasilitas Aksesibilitas</div>
                    @php $fac = (array) request('facility',[]); @endphp
                    @foreach (['Kursi Roda', 'Toilet Disabilitas', 'Ruang Kerja Inklusif', 'Pendamping Kerja', 'Alat Bantu Dengar'] as $item)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $item }}"
                                id="fac{{ $loop->index }}" name="facility[]" form="sideFilter"
                                @checked(in_array($item, $fac))>
                            <label class="form-check-label" for="fac{{ $loop->index }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>
                <form id="sideFilter" method="GET" action="{{ route('lowongan.index') }}"></form>
                <button class="btn btn-outline-secondary w-100 mt-2" form="sideFilter">Terapkan Filter</button>
            </aside>
            <main class="col-lg-9">
                <div class="row g-3">
                    @forelse($lowongans as $job)
                        <div class="col-md-6">
                            <div class="job-card border rounded-3 p-3 h-100 bg-white">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold">{{ $job->perusahaan }}</div>
                                    <span class="text-muted small">
                                        {{ $job->pengalaman ?? '0-1th' }}
                                    </span>
                                </div>
                                <div class="small text-muted">{{ $job->lokasi }}</div>

                                <div class="mt-2 fw-semibold">{{ $job->posisi }}</div>

                                <div class="d-flex gap-2 mt-2">
                                    <span class="chip">{{ $job->tipe_pekerjaan ?? 'Full-Time' }}</span>
                                    @if (!is_null($job->gaji_min) && !is_null($job->gaji_max))
                                        <span class="chip">Rp {{ number_format($job->gaji_min, 0, ',', '.') }} - Rp
                                            {{ number_format($job->gaji_max, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-3">
                                    <small class="text-muted">
                                        {{ optional($job->waktu_posting ?? $job->created_at)->diffForHumans() }}
                                    </small>
                                    <a href="{{ route('lowongan.detail', $job->slug) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border">Belum ada lowongan yang cocok.</div>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $lowongans->withQueryString()->links() }}
                </div>
            </main>
        </div>
    </div>
@endsection
