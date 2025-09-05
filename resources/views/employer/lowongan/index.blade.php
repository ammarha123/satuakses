@extends('adminlte::page')

@section('title', 'Lowongan Perusahaan')

@section('content_header')
    <h1>Lowongan Perusahaan @if($company) <small class="text-muted">— {{ $company->name }}</small> @endif</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('employer.lowongan.create') }}" class="btn btn-primary mb-3">+ Tambah Lowongan</a>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Posisi</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Dibuat</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowongans as $item)
                        <tr>
                            <td>{{ $item->perusahaan }}</td>
                            <td>{{ $item->posisi }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->kategori->nama ?? '-' }}</td>
                            <td>{{ $item->created_at?->diffForHumans() }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                <a href="{{ route('employer.lowongan.show', $item->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('employer.lowongan.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('employer.lowongan.destroy', $item->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada lowongan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($lowongans, 'links'))
            <div class="card-footer">
                {{ $lowongans->links() }}
            </div>
        @endif
    </div>
@endsection
