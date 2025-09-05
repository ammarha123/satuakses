@extends('layouts.app')

@section('title', ($submodule ? $submodule->title : $module->title) . ' | ' . $course->judul)

@section('content')
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header fw-semibold">
                        {{ $course->judul }}
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($course->modules as $m)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-semibold">{{ $m->title }}</div>
                                    <span class="badge bg-light text-dark">{{ $m->submodules->count() }}</span>
                                </div>
                                @if ($m->submodules->count())
                                    <ul class="mt-2 ps-3">
                                        @foreach ($m->submodules as $s)
                                            @php
                                                $isActive =
                                                    $module->id === $m->id && $submodule && $submodule->id === $s->id;
                                                $done = $progressMap[$s->id]->completed_at ?? null ? true : false;
                                            @endphp
                                            <li class="mb-1">
                                                <a class="d-inline-flex align-items-center {{ $isActive ? 'fw-bold' : '' }}"
                                                    href="{{ route('kursus.submodules.show', [$course->slug, $m->id, $s->id]) }}">
                                                    @if ($done)
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                    @else
                                                        <i class="bi bi-circle me-1"></i>
                                                    @endif
                                                    {{ $s->title ?: 'Submodul' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <a href="{{ route('kursus.show', $course->slug) }}" class="text-decoration-none">
                        ← Kembali ke Kursus
                    </a>
                    <div class="small text-muted">{{ $module->title }}</div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if ($submodule)
                            <h4 class="fw-bold mb-3">{{ $submodule->title ?: 'Submodul' }}</h4>

                            @if ($submodule->video_url)
                                <div class="ratio ratio-16x9 mb-3">
                                    <iframe src="{{ $submodule->video_url }}" allowfullscreen></iframe>
                                </div>
                            @endif

                            @if ($submodule->attachment_path)
                                <a class="btn btn-outline-secondary btn-sm mb-3"
                                    href="{{ Storage::disk('public')->url($submodule->attachment_path) }}"
                                    target="_blank"><i class="bi bi-paperclip"></i> Unduh Lampiran</a>
                            @endif

                            <div class="mb-4">{!! nl2br(e($submodule->content)) !!}</div>

                            <div class="d-flex gap-2">

                                <form
                                    action="{{ route('kursus.submodules.prev', [$course->slug, $module->id, $submodule->id]) }}"
                                    method="POST">@csrf
                                    <button class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                                        Sebelumnya</button>
                                </form>
                                <form
                                    action="{{ route('kursus.submodules.complete', [$course->slug, $module->id, $submodule->id]) }}"
                                    method="POST" class="ms-auto">@csrf
                                    <button class="btn btn-success">
                                        <i class="bi bi-check2-circle"></i> Tandai Selesai
                                    </button>
                                </form>

                                @if ($nextUrl)
                                    <form
                                        action="{{ route('kursus.submodules.next', [$course->slug, $module->id, $submodule->id]) }}"
                                        method="POST">@csrf
                                        <button class="btn btn-primary">
                                            Berikutnya <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </form>
                                @else
                                    <form
                                        action="{{ route('kursus.submodules.next', [$course->slug, $module->id, $submodule->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button class="btn btn-primary">
                                            <i class="bi bi-flag"></i> Selesai Modul
                                        </button>
                                    </form>
                                    <div class="">
                                        <a href="{{ route('kursus.show', $course->slug) }}" class="text-decoration-none">
                                            Kembali ke Kursus
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <h4 class="fw-bold mb-3">{{ $module->title }}</h4>
                            <p class="text-muted">Modul ini belum memiliki submodul.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
