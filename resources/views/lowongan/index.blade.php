@extends('layouts.app')

@section('title', 'Lowongan Pekerjaan')

@section('content')

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
                    <img class="hero-illustration img-fluid" src="{{ asset('img/hero-people.svg') }}" alt="">
                </div>
            </div>

            <form class="bg-white shadow-sm rounded-3 p-3 mt-5 floating-search" method="GET"
                action="{{ route('lowongan.index') }}" id="mainSearchForm">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <label class="small text-muted">Lokasi</label>
                        <select name="lokasi" class="form-control">
                            <option value="">-- Semua Lokasi --</option>
                            @foreach ($lokasis as $loc)
                                <option value="{{ $loc }}" {{ request('lokasi') == $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="small text-muted">Kategori</label>

                        @if ($categories->isEmpty())
                            <!-- nothing to show -->
                            <select name="kategori" class="form-select">
                                <option value="">Semua</option>
                            </select>
                        @else
                            @php
                                $isAssoc = $categories->keys()->first() !== 0;
                            @endphp

                            <select name="kategori" class="form-select">
                                <option value="">Semua</option>

                                @if ($isAssoc)
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" @selected((string) request('kategori') === (string) $id)>{{ $name }}
                                        </option>
                                    @endforeach
                                @else
                                    {{-- categories is list of plain labels or numeric ids --}}
                                    @foreach ($categories as $c)
                                        <option value="{{ $c }}" @selected((string) request('kategori') === (string) $c)>{{ $c }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </div>

                    <div class="col-md-5">
                        <label class="small text-muted">Rentang Gaji (opsional)</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="salary_min_display" class="form-control rupiah-input"
                                placeholder="Rp 1.000.000" value="{{ request('salary_min_display', '') }}">
                            <input type="text" id="salary_max_display" class="form-control rupiah-input"
                                placeholder="Rp 5.000.000" value="{{ request('salary_max_display', '') }}">
                        </div>
                        <input type="hidden" name="salary_min" id="salary_min" value="{{ request('salary_min') }}">
                        <input type="hidden" name="salary_max" id="salary_max" value="{{ request('salary_max') }}">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button class="btn btn-primary mt-4">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="container py-4">
        <div class="row g-4">
            <aside class="col-lg-3">
                <h6 class="fw-bold mb-3">Daftar Pekerjaan</h6>

                {{-- SORT --}}
                <div class="filter-box p-3 mb-3">
                    <div class="fw-semibold mb-2">Urut Berdasarkan</div>

                    {{-- use a select to include salary sort options --}}
                    <form id="sideFilter" method="GET" action="{{ route('lowongan.index') }}">
                        {{-- keep existing query params so filters persist --}}
                        <input type="hidden" name="lokasi" value="{{ request('lokasi') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="disability" value="{{ request('disability') }}">
                        <input type="hidden" name="salary_min" id="side_salary_min" value="{{ request('salary_min') }}">
                        <input type="hidden" name="salary_max" id="side_salary_max" value="{{ request('salary_max') }}">

                        <select name="sort" class="form-select">
                            <option value="relevan" @selected(request('sort') == 'relevan')>Paling Relevan</option>
                            <option value="baru" @selected(request('sort') == 'baru')>Baru Ditambahkan</option>

                            {{-- salary sorts --}}
                            <option value="gaji_min_asc" @selected(request('sort') == 'gaji_min_asc')>Gaji Minimum (terendah)</option>
                            <option value="gaji_min_desc" @selected(request('sort') == 'gaji_min_desc')>Gaji Minimum (tertinggi)</option>

                            <option value="gaji_max_asc" @selected(request('sort') == 'gaji_max_asc')>Gaji Maksimum (terendah)</option>
                            <option value="gaji_max_desc" @selected(request('sort') == 'gaji_max_desc')>Gaji Maksimum (tertinggi)</option>
                        </select>
                    </form>
                </div>
            </aside>

            <main class="col-lg-9">
                <div class="row g-3">
                    @forelse($lowongans as $job)
                        <div class="col-md-6">
                            <div class="job-card border rounded-3 p-4 h-100 bg-white shadow-sm hover-card">
                                {{-- Company + experience --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        @php
    $companyName = trim($job->perusahaan ?? '');
    $company = $companyMap->get($companyName);
@endphp

@if ($company)
    <a href="{{ route('companies.show', $company->id) }}" class="fw-bold text-primary text-decoration-none">
        {{ $company->name }}
    </a>
@else
    <span class="fw-bold text-primary">{{ $job->perusahaan }}</span>
@endif

                                        <div class="small text-muted">{{ $job->lokasi }}</div>
                                    </div>
                                    <span class="badge bg-light text-dark small">
                                        {{ $job->pengalaman ?? '0-1 thn' }}
                                    </span>
                                </div>

                                {{-- Position --}}
                                <h5 class="fw-semibold mb-2 text-dark">{{ $job->posisi }}</h5>

                                {{-- Chips --}}
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="chip">{{ $job->tipe_pekerjaan ?? 'Full-Time' }}</span>

                                    @if ($job->kategori)
                                        <span class="chip chip-blue">{{ $job->kategori->nama ?? '-' }}</span>
                                    @endif

                                    @if (!is_null($job->gaji_min) && !is_null($job->gaji_max))
                                        <span class="chip">
                                            Rp {{ number_format($job->gaji_min, 0, ',', '.') }} -
                                            Rp {{ number_format($job->gaji_max, 0, ',', '.') }}
                                        </span>
                                    @elseif (!is_null($job->gaji_min))
                                        <span class="chip">
                                            Rp {{ number_format($job->gaji_min, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Footer --}}
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        {{ optional($job->waktu_posting ?? $job->created_at)->diffForHumans() }}
                                    </small>
                                    <a href="{{ route('lowongan.detail', $job->slug) }}" class="btn btn-sm btn-primary">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // format input as Rupiah on typing (visual only)
            function formatRupiahInput(el) {
                el.addEventListener('input', function() {
                    const cleaned = this.value.replace(/[^0-9]/g, '');
                    if (!cleaned) {
                        this.value = '';
                        return;
                    }
                    const n = parseInt(cleaned, 10);
                    this.value = 'Rp ' + n.toLocaleString('id-ID');
                });
            }

            const minDisplay = document.getElementById('salary_min_display');
            const maxDisplay = document.getElementById('salary_max_display');

            if (minDisplay) formatRupiahInput(minDisplay);
            if (maxDisplay) formatRupiahInput(maxDisplay);

            // when main search form submits, copy numeric values into hidden fields
            const mainSearchForm = document.getElementById('mainSearchForm');
            if (mainSearchForm) {
                mainSearchForm.addEventListener('submit', function(e) {
                    const minHidden = document.getElementById('salary_min');
                    const maxHidden = document.getElementById('salary_max');

                    const parseNumber = (val) => {
                        if (!val) return '';
                        return val.toString().replace(/[^0-9]/g, '');
                    };

                    if (minDisplay) minHidden.value = parseNumber(minDisplay.value);
                    if (maxDisplay) maxHidden.value = parseNumber(maxDisplay.value);
                });
            }

            // also sync sidebar hidden salary inputs when sideFilter submitted
            const sideForm = document.getElementById('sideFilter');
            if (sideForm) {
                sideForm.addEventListener('submit', function() {
                    const sideMin = document.getElementById('side_salary_min');
                    const sideMax = document.getElementById('side_salary_max');

                    const getFromMain = (id) => document.getElementById(id) ? document.getElementById(id)
                        .value.replace(/[^0-9]/g, '') : '';

                    sideMin.value = getFromMain('salary_min') || getFromMain('salary_min_display') ||
                        getFromMain('add_salary_min') || '';
                    sideMax.value = getFromMain('salary_max') || getFromMain('salary_max_display') ||
                        getFromMain('add_salary_max') || '';
                });
            }
        });
    </script>
@endpush
