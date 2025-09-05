<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kesempatan Setara') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts (optional for nicer headings) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link href="{{ asset('css/global.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .section-title {
            font-weight: 700;
            font-size: 2rem;
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('index') }}">SatuAkses</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item"><a class="nav-link" href="{{ route('lowongan.index') }}">Pekerjaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('kursus.index') }}">Kursus</a></li>

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-sm ms-lg-2"
                                href="{{ route('register') }}">Daftar</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                id="profileMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fw-semibold">{{ Str::limit(Auth::user()->name, 14) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileMenu">

                                @role('admin')
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Admin Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endrole
                                @role('employer')
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('employer.dashboard') }}">
                                            <i class="bi bi-building"></i> Company Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endrole

                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person"></i> Akun Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('user.lamaran.index') }}">
                                        <i class="bi bi-briefcase"></i> Lamaran Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('user.mycourses.index') }}">
                                        <i class="bi bi-mortarboard"></i> Kursus Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('settings.index') }}">
                                        <i class="bi bi-gear"></i> Pengaturan
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item d-flex align-items-center gap-2">
                                            <i class="bi bi-box-arrow-right"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-light py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Kesempatan Setara. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
