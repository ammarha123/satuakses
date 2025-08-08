@extends('adminlte::page')

@section('title', 'Kelola Lowongan')

@section('content_header')
    <h1>Kelola Lowongan</h1>
@endsection

@section('content')
    <a href="{{ route('admin.lowongan.create') }}" class="btn btn-primary mb-3">+ Tambah Lowongan</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Perusahaan</th>
                <th>Posisi</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Dibuat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowongans as $item)
                <tr>
                    <td>{{ $item->perusahaan }}</td>
                    <td>{{ $item->posisi }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td>{{ $item->created_at->diffForHumans() }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="{{ route('admin.lowongan.show', $item) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('admin.lowongan.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.lowongan.destroy', $item) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada data lowongan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
