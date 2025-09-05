@extends('adminlte::page')

@section('title', 'Akun Perusahaan Dibuat')

@section('content_header')
    <h1>Akun Perusahaan Berhasil Dibuat</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $company->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $company->email }} <br>
                <strong>Password:</strong> {{ $password }} <br>
                <strong>Website:</strong> {{ $company->website ?? '-' }} <br>
                <strong>Lokasi:</strong> {{ $company->city ?? '-' }}, {{ $company->province ?? '-' }}
            </p>

            <div class="mt-3">
                <textarea id="shareText" class="form-control" rows="4" readonly>
Akun perusahaan berhasil dibuat!
Nama: {{ $company->name }}
Email: {{ $company->email }}
Password: {{ $password }}
Login di: {{ url('/login') }}
            </textarea>

                <button onclick="copyText()" class="btn btn-primary mt-2">
                    <i class="fas fa-copy"></i> Salin & Bagikan
                </button>
            </div>
        </div>
    </div>

    <script>
        function copyText() {
            let textArea = document.getElementById('shareText');
            textArea.select();
            document.execCommand('copy');
            alert('Teks berhasil disalin!');
        }
    </script>
@endsection
