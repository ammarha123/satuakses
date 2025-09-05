@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4">Profil</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm mt-4">
                    <div class="card-header fw-semibold">Ganti Kata Sandi</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button class="btn btn-primary w-100">Simpan Password</button>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-header text-danger fw-semibold">Hapus Akun</div>
                    <div class="card-body">
                        <p class="small text-muted">Tindakan ini permanen dan tidak bisa dibatalkan.</p>
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf @method('DELETE')
                            <div class="mb-2">
                                <input type="text" name="confirm" class="form-control"
                                    placeholder='Ketik "HAPUS" untuk konfirmasi'>
                                @error('confirm')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-outline-danger w-100">Hapus Akun</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Detail Profil</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf @method('PATCH')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control" required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control" required>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="form-control">
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select">
                                        <option value="" {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>Pilih
                                        </option>
                                        <option value="Laki-laki"
                                            {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan"
                                            {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" name="province" value="{{ old('province', $user->province) }}"
                                        class="form-control">
                                    @error('province')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kota/Kab</label>
                                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                        class="form-control">
                                    @error('city')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Bio / Ringkasan</label>
                                    <textarea name="bio" rows="4" class="form-control" placeholder="Ceritakan dirimu singkat...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
