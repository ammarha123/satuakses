@extends('adminlte::page')

@section('title', 'Kelola Kursus')

@section('content_header')
    <h1>Kelola Kursus</h1>
@endsection

@section('content')
    <a href="{{ route('admin.kursus.create') }}" class="btn btn-primary mb-3">+ Tambah Kursus</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tingkat</th>
                <th>Tanggal Mulai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kursus as $item)
                <tr>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->tingkat ?? '-' }}</td>
                    <td>{{ $item->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.kursus.show', $item) }}" class="btn btn-info btn-sm">Lihat</a>
                        <a href="{{ route('admin.kursus.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.kursus.destroy', $item) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data kursus.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
