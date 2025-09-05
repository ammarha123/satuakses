<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar - SatuAkses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #DFF6FA;
            font-family: 'Poppins', sans-serif;
        }

        .register-card {
            max-width: 500px;
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

        .btn-register {
            background: linear-gradient(to right, #1d4ed8, #3b82f6);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 16px;
            padding: 10px;
            width: 100%;
        }

        .btn-register:hover {
            background: #42bee3;
        }

        .form-label {
            margin-bottom: 4px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="register-card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <b>Form tidak valid:</b>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h4 class="fw-bold text-center mb-4">Buat Akun</h4>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="name" name="name" class="form-control" placeholder="Masukkan nama kamu" required
                        value="{{ old('name') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email kamu" required
                        value="{{ old('email') }}">
                </div>

                <div class="mb-2">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Kata Sandi..."
                        required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Ketik Ulang Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Masukkan Ulang Kata Sandi..." required>
                </div>

                <div class="mb-2">
                    <label class="form-label">No HP</label>
                    <input type="text" name="phone" class="form-control" placeholder="Masukkan No HP..." required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Provinsi</label>
                    <input type="text" name="province" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Kab/Kota</label>
                    <input type="text" name="city" class="form-control" required>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="my-3">
                    <button type="submit" class="btn-register">Daftar</button>
                </div>

                <div class="text-center">
                    <small>Sudah punya akun? <a href="{{ route('login') }}" class="text-primary">Masuk di
                            sini</a></small>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
