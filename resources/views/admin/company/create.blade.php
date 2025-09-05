@extends('adminlte::page')

@section('title','Tambah Perusahaan')
@section('content_header') <h1>Tambah Perusahaan</h1> @endsection

@section('content')
@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif

<form method="POST" action="{{ route('admin.company.store') }}" class="row g-3">
  @csrf
  <div class="col-md-6">
    <label class="form-label">Nama Perusahaan</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Email (login perusahaan)</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Telepon</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Website</label>
    <input type="url" name="website" class="form-control" value="{{ old('website') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Provinsi</label>
    <input type="text" name="province" class="form-control" value="{{ old('province') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Kota/Kabupaten</label>
    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
  </div>
  <div class="col-12">
    <label class="form-label">Alamat</label>
    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
  </div>
  <div class="col-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
  </div>
  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="active">Active</option>
      <option value="pending">Pending</option>
      <option value="suspended">Suspended</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Simpan & Buat Akun</button>
    <a href="{{ route('admin.company.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</form>
@endsection
