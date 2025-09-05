@extends('adminlte::page')

@section('title', 'Detail Lamaran')

@section('content_header')
    <h1>Detail Lamaran</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-2">{{ $application->user->name }} <small
                    class="text-muted">({{ $application->user->email }})</small></h5>
            <p class="mb-1"><b>Lowongan:</b> {{ $application->lowongan->posisi }} —
                {{ $application->lowongan->perusahaan }}</p>
            <p class="mb-1"><b>Dikirim:</b> {{ $application->submitted_at?->format('d M Y H:i') }}</p>
            <p class="mb-1"><b>Status:</b> {{ ucfirst($application->status) }}</p>

            @if ($application->cover_letter)
                <hr>
                <h6>Cover Letter</h6>
                <div>{!! nl2br(e($application->cover_letter)) !!}</div>
            @endif

            @if ($application->cv_path)
                <hr>
                <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    Lihat / Unduh CV
                </a>
            @endif
        </div>

        <div class="card-footer d-flex gap-2">
            <form action="{{ route('employer.applications.updateStatus', $application->id) }}" method="post"
                class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="reviewed">
                <button class="btn btn-secondary btn-sm">Tandai Reviewed</button>
            </form>
            <form action="{{ route('employer.applications.updateStatus', $application->id) }}" method="post"
                class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="shortlisted">
                <button class="btn btn-warning btn-sm">Shortlist</button>
            </form>
            <form action="{{ route('employer.applications.updateStatus', $application->id) }}" method="post"
                class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button class="btn btn-danger btn-sm">Tolak</button>
            </form>
            <form action="{{ route('employer.applications.updateStatus', $application->id) }}" method="post"
                class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="hired">
                <button class="btn btn-success btn-sm">Hire</button>
            </form>
        </div>
    </div>
@endsection
