@extends('layouts.app')

@section('title', 'Quiz: ' . $quiz->title)

@section('content')
    <div class="container py-4">
        <a href="{{ route('kursus.show', $course->slug) }}" class="text-decoration-none mb-3 d-inline-block">← Kembali ke
            Kursus</a>

        <div class="row g-3">
            <div class="col-lg-3">
                <div class="card sticky-top" style="top: 80px">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Nomor Soal</strong>
                            @if ($quiz->time_limit)
                                <span id="time-remaining" class="badge text-bg-warning">--:--</span>
                            @else
                                <span class="badge text-bg-secondary">Tanpa batas</span>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap gap-2" id="q-nav">
                            @foreach ($quiz->questions as $i => $q)
                                <a href="#q-{{ $q->id }}" class="btn btn-sm btn-outline-secondary q-btn"
                                    data-qid="{{ $q->id }}">{{ $i + 1 }}</a>
                            @endforeach
                        </div>

                        @if (isset($lastScore))
                            <div class="mt-3 small">
                                Skor terakhir: <strong>{{ $lastScore }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <h3 class="fw-bold mb-1">{{ $quiz->title }}</h3>
                <div class="text-muted mb-3">
                    Batas waktu: {{ $quiz->time_limit ? $quiz->time_limit . ' menit' : '-' }}
                </div>

                <form id="quiz-form" method="POST" action="{{ route('kursus.quiz.submit', [$course->slug, $quiz->id]) }}">
                    @csrf

                    @foreach ($quiz->questions as $i => $q)
                        <div class="card mb-3" id="q-{{ $q->id }}">
                            <div class="card-body">
                                <div class="fw-semibold mb-2">{{ $i + 1 }}. {{ $q->question }}</div>
                                @foreach ($q->options as $oi => $opt)
                                    <div class="form-check">
                                        <input class="form-check-input ans-radio" type="radio"
                                            name="answers[{{ $q->id }}]"
                                            id="q{{ $q->id }}o{{ $oi }}" value="{{ $oi }}"
                                            data-qid="{{ $q->id }}">
                                        <label for="q{{ $q->id }}o{{ $oi }}" class="form-check-label">
                                            {{ $opt->text ?? $opt }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex gap-2">
                        <button type="button" id="submit-btn" class="btn btn-primary">
                            Kumpulkan Jawaban
                        </button>
                        <a href="{{ route('kursus.show', $course->slug) }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const nav = document.getElementById('q-nav');
            const radios = document.querySelectorAll('.ans-radio');
            const qBtns = {};
            document.querySelectorAll('.q-btn').forEach(btn => {
                qBtns[btn.dataset.qid] = btn;
            });

            function markAnswered(qid) {
                const btn = qBtns[qid];
                if (!btn) return;
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success', 'text-white');
            }
            radios.forEach(r => {
                r.addEventListener('change', () => markAnswered(r.dataset.qid));
            });
            const submitBtn = document.getElementById('submit-btn');
            const form = document.getElementById('quiz-form');
            submitBtn.addEventListener('click', () => {
                if (confirm('Apakah Anda yakin akan mengumpulkan jawaban?')) {
                    form.submit();
                }
            });
            @if ($quiz->time_limit)
                let remain = {{ (int) $quiz->time_limit }} * 60;
                const el = document.getElementById('time-remaining');

                const tick = () => {
                    const m = Math.floor(remain / 60);
                    const s = remain % 60;
                    el.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
                    if (remain <= 0) {
                        el.classList.remove('text-bg-warning');
                        el.classList.add('text-bg-danger');

                        form.submit();
                        return;
                    }
                    remain--;
                    setTimeout(tick, 1000);
                };
                tick();
            @endif
        })();
    </script>
@endsection
