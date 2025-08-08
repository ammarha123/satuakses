<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SatuAkses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap (optional if you use utilities only) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #DFF6FA;
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            max-width: 420px;
            margin: 80px auto;
            padding: 30px 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: 2px solid black;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: black;
            box-shadow: none;
        }

        .btn-login {
            background: linear-gradient(to right, #1d4ed8, #3b82f6);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 16px;
            padding: 10px;
            width: 100%;
        }

        .btn-login:hover {
            background: linear-gradient(to right, #2563eb, #60a5fa);
        }

        .text-small {
            font-size: 14px;
        }

        .text-link {
            color: #3b82f6;
            text-decoration: none;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .brand {
            text-align: center;
            font-weight: 600;
            font-size: 42px;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="brand mt-5">SatuAkses</div>
        <div class="login-card">
            <h5 class="fw-bold mb-2">Masuk ke Akunmu</h5>
            <p class="text-small text-muted mb-4">
                Akses pelatihan dan lowongan kerja<br>yang dirancang khusus untukmu.
            </p>

            @if (session('status'))
                <div class="alert alert-success text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email kamu" required value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi..." required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 text-end">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-small text-dark">Lupa kata sandi?</a>
                    @endif
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn-login">Masuk</button>
                </div>

                <div class="text-center text-small">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-link">Daftar di sini</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
