@extends('adminlte::page')

@section('title','Daftar Perusahaan')
@section('content_header')
    <h1>Daftar Perusahaan</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.company.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Perusahaan
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Perusahaan</th>
                        <th>Email Login</th>
                        <th>Kota</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>{{ $loop->iteration + ($companies->currentPage() - 1) * $companies->perPage() }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->city ?? '-' }}</td>
                            <td>
                                @if($company->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($company->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Suspended</span>
                                @endif
                            </td>
                            <td>{{ $company->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="#" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin hapus perusahaan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data perusahaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
@stop
