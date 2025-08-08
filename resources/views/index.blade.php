@extends('layouts.app')

@section('content')
    <div class="hero position-relative">
        <h1>Kesempatan yang Setara,<br>Karier yang Bermakna.</h1>
        <p>Temukan pelatihan yang dirancang khusus untuk kamu,<br>dan dapatkan pekerjaan yang sesuai dengan potensimu.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="#" class="btn btn-light px-4 py-2">Mulai Sekarang!</a>
            <a href="#" class="btn btn-light px-4 py-2">Lihat Lowongan</a>
        </div>

        {{-- Elevated Cards --}}
        <div class="container position-absolute start-50 translate-middle-x"
            style="top: 80%; transform: translate(-50%, -50%); z-index: 10;">
            <div class="row text-center justify-content-center">
                <div class="col-md-6 mb-4">
                    <div class="highlight-box shadow">
                        <img src="{{ asset('img/kursus.svg') }}" alt="Kursus" class="img-fluid mb-3">
                        <h2 class="fw-bold">Kursus Siap Kerja</h2>
                        <p>Latihan kerja yang dirancang khusus untuk meningkatkan kesiapanmu menghadapi dunia kerja. Mulai
                            dari pengenalan teknologi aksesibel, keterampilan komunikasi, hingga simulasi wawancara kerja.
                            Dapatkan pembelajaran yang praktis, inklusif, dan bisa langsung diterapkan.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="highlight-box shadow">
                        <img src="{{ asset('img/pekerjaan.svg') }}" alt="Pekerjaan" class="img-fluid mb-3">
                        <h2 class="fw-bold">Pekerjaan Ramah Difabel</h2>
                        <p>Temukan peluang kerja dari perusahaan yang peduli dan berkomitmen pada keberagaman serta inklusi.
                            Lowongan yang tersedia telah diseleksi agar ramah bagi penyandang disabilitas, dengan lingkungan
                            kerja yang suportif dan fasilitas yang memadai. Bangun kariermu tanpa hambatan!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container main-content">
        <h5 class="fw-bold mt-5">Lowongan Tersedia</h5>
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" href="#">Pendidikan</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Design Grafis</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Wirausaha</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Administrasi</a></li>
        </ul>

        <div class="row">
            @foreach ($lowongans as $lowongan)
                <div class="col-md-4 mb-3">
                    <div class="border p-3 rounded">
                        <h6 class="fw-bold">{{ $lowongan->perusahaan }}</h6>
                        <p class="mb-1 text-muted">{{ $lowongan->lokasi }}</p>
                        <p class="mb-1">{{ $lowongan->posisi }}</p>
                        <p class="text-muted small">{{ $lowongan->waktu_posting->diffForHumans() }}</p>
                        <a href="#" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="#" class="text-primary mt-3 d-inline-block">Lihat lainnya ...</a>

        <h5 class="fw-bold mt-5">Daftar Kursus</h5>
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" href="#">Administrasi</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Design Grafis</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Wirausaha</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Pendidikan</a></li>
        </ul>

        <div class="row">
            @foreach ($courses as $course)
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 bg-light h-100">
                        <h6 class="fw-bold">{{ $course->judul }}</h6>
                        <p class="text-muted small">Pelatihan {{ $course->tingkat }}</p>
                        <a href="#" class="btn btn-outline-dark btn-sm">Ikuti</a>
                    </div>
                </div>
            @endforeach
        </div>
        <a href="#" class="text-primary mt-3 d-inline-block">Lihat lainnya ...</a>
    </div>
@endsection
