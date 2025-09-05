@extends('layouts.app')

@section('title', $module->title . ' | ' . $course->judul)

@section('content')
    <div class="container py-4">
        <a href="{{ route('kursus.show', $course->slug) }}" class="text-decoration-none mb-3 d-inline-block">
            ← Kembali ke Kursus
        </a>

        <h3 class="fw-bold">{{ $module->title }}</h3>
        <div class="text-muted mb-3">{{ $module->summary }}</div>

        @if ($module->video_url)
            <div class="ratio ratio-16x9 mb-3">
                <iframe src="{{ $module->video_url }}" allowfullscreen></iframe>
            </div>
        @endif
        @if (method_exists($module, 'attachments') && $module->attachments->count())
            <div class="card">
                <div class="card-header">Materi Tambahan</div>
                <div class="card-body">
                    @foreach ($module->attachments as $att)
                        <div class="mb-2">
                            <a href="{{ \Storage::url($att->file_path) }}"
                                target="_blank">{{ $att->title ?? basename($att->file_path) }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
