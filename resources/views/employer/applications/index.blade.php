@extends('adminlte::page')

@section('title', 'Lamaran Masuk')

@section('content_header')
    <h1>Lamaran Masuk
        @if ($company)
            <small class="text-muted">— {{ $company->name }}</small>
        @endif
    </h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Pelamar</th>
                        <th>Email</th>
                        <th>Lowongan</th>
                        <th>Dikirim</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        <tr>
                            <td>{{ $app->user->name }}</td>
                            <td>{{ $app->user->email }}</td>
                            <td>{{ $app->lowongan->posisi ?? '-' }}</td>
                            <td>{{ $app->submitted_at?->diffForHumans() }}</td>
                            <td>{{ ucfirst($app->status) }}</td>
                            <td>
                                <a href="{{ route('employer.applications.show', $app->id) }}"
                                    class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada lamaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $applications->links() }}
        </div>
    </div>
@endsection
