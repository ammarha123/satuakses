<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseSubmodule;
use App\Models\CourseSubmoduleProgress;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CoursePublicController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string)$request->get('q', ''));
        $kategori = trim((string)$request->get('kategori', ''));
        $tingkat  = trim((string)$request->get('tingkat', ''));

        $courses = Course::query()
            ->when(
                $q,
                fn($qr) =>
                $qr->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                        ->orWhere('deskripsi', 'like', "%{$q}%");
                })
            )
            ->when($kategori, fn($qr) => $qr->where('kategori', $kategori))
            ->when($tingkat,  fn($qr) => $qr->where('tingkat', $tingkat))
            ->withCount(['modules', 'enrollments', 'quizzes'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Course::select('kategori')->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $levels     = Course::select('tingkat')->whereNotNull('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');

        return view('user.kursus.index', [
            'courses'   => $courses,
            'q'         => $q,
            'kategori'  => $kategori,
            'tingkat'   => $tingkat,
            'categories' => $categories,
            'levels'    => $levels,
        ]);
    }

    public function show(string $slug)
    {
        $course = Course::with([
            'modules' => fn($q) => $q->orderBy('sort_order')->with(['submodules' => fn($q2) => $q2->orderBy('sort_order')]),
            'quizzes',
        ])
            ->withCount(['enrollments'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = auth()->user();
        $isEnrolled = false;
        if ($user) {
            $isEnrolled = $course->users()->where('users.id', $user->id)->exists();
        }

        $selectedModuleId    = request('m');
        $selectedSubmoduleId = request('s');

        $selectedModule = null;
        $selectedSubmodule = null;

        if ($selectedModuleId && $selectedSubmoduleId) {
            $selectedModule = $course->modules->firstWhere('id', (int)$selectedModuleId);
            if ($selectedModule) {
                $selectedSubmodule = $selectedModule->submodules->firstWhere('id', (int)$selectedSubmoduleId);
            }
            if ($isEnrolled && $selectedModule && $selectedSubmodule) {
                CourseSubmoduleProgress::firstOrCreate([
                    'user_id'      => $user->id,
                    'course_id'    => $course->id,
                    'module_id'    => $selectedModule->id,
                    'submodule_id' => $selectedSubmodule->id,
                ]);
            }
        }

        $progressMap = collect();
        if ($user) {
            $ids = $course->modules->flatMap->submodules->pluck('id');
            if ($ids->count()) {
                $progressMap = CourseSubmoduleProgress::where('user_id', $user->id)
                    ->whereIn('submodule_id', $ids)
                    ->get()
                    ->keyBy('submodule_id');
            }
        }

        $totalSub = $course->modules->flatMap->submodules->count();
        $viewed   = $progressMap->count();
        $allViewed = $totalSub > 0 ? ($viewed >= $totalSub) : true;

        return view('user.kursus.show', compact(
            'course',
            'isEnrolled',
            'selectedModule',
            'selectedSubmodule',
            'progressMap',
            'allViewed'
        ));
    }

    public function enroll(Request $request, string $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $course->users()->syncWithoutDetaching([Auth::id()]);

        return redirect()->route('kursus.learn', $course->slug)
            ->with('success', 'Berhasil mendaftar. Selamat belajar!');
    }

    public function startLearning(string $slug)
    {
        $course = Course::with(['modules.submodules'])->where('slug', $slug)->firstOrFail();

        $this->ensureEnrolled($course);

        $firstModule = $course->modules->first();
        if (!$firstModule) {
            return back()->with('error', 'Belum ada modul pada kursus ini.');
        }

        $firstSub = $firstModule->submodules->first();
        if ($firstSub) {
            return redirect()->route('kursus.submodules.show', [$course->slug, $firstModule->id, $firstSub->id]);
        }
        return redirect()->route('kursus.modules.show', [$course->slug, $firstModule->id]);
    }

    public function showModule(string $slug, int $moduleId)
    {
        $course = Course::with('modules.submodules')->where('slug', $slug)->firstOrFail();
        $this->ensureEnrolled($course);

        $module = $course->modules->firstWhere('id', $moduleId);
        abort_unless($module, 404);

        if ($module->submodules->count()) {
            return redirect()->route('kursus.submodules.show', [$course->slug, $module->id, $module->submodules->first()->id]);
        }

        return view('user.kursus.learn.module', [
            'course' => $course,
            'module' => $module,
            'submodule' => null,
            'progressMap' => [],
            'nextUrl' => null,
        ]);
    }

    public function showSubmodule(string $slug, int $moduleId, int $submoduleId)
    {
        $course = Course::with('modules.submodules')->where('slug', $slug)->firstOrFail();
        $this->ensureEnrolled($course);

        $module = $course->modules->firstWhere('id', $moduleId);
        abort_unless($module, 404);

        $submodule = $module->submodules->firstWhere('id', $submoduleId);
        abort_unless($submodule, 404);

        $progressRows = CourseSubmoduleProgress::where('user_id', Auth::id())
            ->whereIn('submodule_id', $course->modules->flatMap->submodules->pluck('id'))
            ->get()->keyBy('submodule_id');

        $next = $this->findNext($course, $module, $submodule);
        $nextUrl = $next
            ? route('kursus.submodules.show', [$course->slug, $next['module']->id, $next['submodule']->id])
            : null;

        return view('user.kursus.learn.module', compact('course', 'module', 'submodule', 'progressRows', 'nextUrl'));
    }

    public function completeSubmodule(string $slug, int $moduleId, int $submoduleId)
    {
        $course = Course::with('modules.submodules')->where('slug', $slug)->firstOrFail();
        $this->ensureEnrolled($course);

        $module = $course->modules->firstWhere('id', $moduleId);
        abort_unless($module, 404);
        $sub    = $module->submodules->firstWhere('id', $submoduleId);
        abort_unless($sub, 404);

        CourseSubmoduleProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'submodule_id' => $sub->id],
            ['course_id' => $course->id, 'module_id' => $module->id, 'completed_at' => now()]
        );

        return back()->with('success', 'Submodul ditandai selesai.');
    }

    public function next(string $slug, int $moduleId, int $submoduleId)
    {
        $course = Course::with('modules.submodules')->where('slug', $slug)->firstOrFail();
        $this->ensureEnrolled($course);

        $module = $course->modules->firstWhere('id', $moduleId);
        abort_unless($module, 404);
        $sub    = $module->submodules->firstWhere('id', $submoduleId);
        abort_unless($sub, 404);

        CourseSubmoduleProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'submodule_id' => $sub->id],
            ['course_id' => $course->id, 'module_id' => $module->id, 'completed_at' => now()]
        );

        $next = $this->findNext($course, $module, $sub);
        return $next
            ? redirect()->route('kursus.submodules.show', [$course->slug, $next['module']->id, $next['submodule']->id])
            : back()->with('success', 'Kamu telah menyelesaikan semua materi 🎉');
    }

    public function prev(string $slug, int $moduleId, int $submoduleId)
    {
        $course = Course::with('modules.submodules')->where('slug', $slug)->firstOrFail();
        $this->ensureEnrolled($course);

        $module = $course->modules->firstWhere('id', $moduleId);
        abort_unless($module, 404);
        $sub    = $module->submodules->firstWhere('id', $submoduleId);
        abort_unless($sub, 404);

        $prev = $this->findPrev($course, $module, $sub);
        return $prev
            ? redirect()->route('kursus.submodules.show', [$course->slug, $prev['module']->id, $prev['submodule']->id])
            : back();
    }

    protected function ensureEnrolled(Course $course): void
    {
        if (!Auth::check() || !$course->users()->where('users.id', Auth::id())->exists()) {
            abort(403, 'Anda belum terdaftar di kursus ini.');
        }
    }

    protected function ensureSameCourseByIds(Course $course, CourseModule $module): void
    {
        if ((int)$module->course_id !== (int)$course->id) {
            abort(404);
        }
    }

    protected function ensureModuleOwnsSub(CourseModule $module, CourseSubmodule $submodule): void
    {
        if ((int)$submodule->module_id !== (int)$module->id) {
            abort(404);
        }
    }

    protected function findNext(Course $course, CourseModule $module, CourseSubmodule $submodule): ?array
    {
        $mods = $course->modules->values();
        $curMIndex = $mods->search(fn($m) => $m->id === $module->id);

        $subs = $module->submodules->values();
        $curSIndex = $subs->search(fn($s) => $s->id === $submodule->id);

        if ($curSIndex !== false && $curSIndex + 1 < $subs->count()) {
            return ['module' => $module, 'submodule' => $subs[$curSIndex + 1]];
        }
        if ($curMIndex !== false && $curMIndex + 1 < $mods->count()) {
            $nextMod = $mods[$curMIndex + 1];
            $nextMod->loadMissing('submodules');
            if ($nextMod->submodules->count()) {
                return ['module' => $nextMod, 'submodule' => $nextMod->submodules->first()];
            }
        }
        return null;
    }

    public function startQuiz(string $slug, Quiz $quiz)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        abort_unless($quiz->course_id === $course->id, 404);

        $quiz->load('questions.options');

        $lastScore = null;
        if (Schema::hasTable('quiz_attempts')) {
            $lastScore = DB::table('quiz_attempts')
                ->where('user_id', auth()->id())
                ->where('quiz_id', $quiz->id)
                ->orderByDesc('id')
                ->value('score');
        } else {
            $lastScore = session("quiz_scores.{$quiz->id}");
        }

        return view('user.kursus.quiz', compact('course', 'quiz', 'lastScore'));
    }

    public function submitQuiz(Request $request, string $slug, Quiz $quiz)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $this->authorizeAccess($course);
        abort_unless($quiz->course_id === $course->id, 404);

        $data = $request->validate(['answers' => 'required|array']);
        $quiz->load('questions.options');

        $total   = max(1, $quiz->questions->count());
        $correct = 0;

        foreach ($quiz->questions as $q) {
            $ansIdx = $data['answers'][$q->id] ?? null;
            if (!is_null($ansIdx) && isset($q->options[$ansIdx]) && (int)$q->correct_index === (int)$ansIdx) {
                $correct++;
            }
        }

        $score  = (int) round(($correct / $total) * 100);
        $passed = $score >= 75;

        if (Schema::hasTable('quiz_attempts')) {
            $attemptId = DB::table('quiz_attempts')->insertGetId([
                'user_id'    => auth()->id(),
                'quiz_id'    => $quiz->id,
                'score'      => $score,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (Schema::hasTable('quiz_answers')) {
                foreach ($quiz->questions as $q) {
                    $ansIdx = $data['answers'][$q->id] ?? null;
                    DB::table('quiz_answers')->insert([
                        'attempt_id'  => $attemptId,
                        'question_id' => $q->id,
                        'is_correct'  => (!is_null($ansIdx) && (int)$q->correct_index === (int)$ansIdx),
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }
        } else {
            session(["quiz_scores.{$quiz->id}" => $score]);
        }

        return redirect()
            ->route('kursus.quiz.result', [$course->slug, $quiz->id])
            ->with([
                'score'   => $score,
                'passed'  => $passed,
                'total'   => $total,
                'correct' => $correct,
            ]);
    }

    public function resultQuiz(string $slug, Quiz $quiz)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        abort_unless($quiz->course_id === $course->id, 404);

        $score = session('score');
        if (is_null($score) && Schema::hasTable('quiz_attempts')) {
            $score = DB::table('quiz_attempts')
                ->where('user_id', auth()->id())
                ->where('quiz_id', $quiz->id)
                ->orderByDesc('id')
                ->value('score');
        }
        $passed  = session()->has('passed') ? (bool)session('passed') : ($score >= 75);
        $total   = session('total');
        $correct = session('correct');

        return view('user.kursus.quiz_result', compact('course', 'quiz', 'score', 'passed', 'total', 'correct'));
    }

    protected function authorizeAccess(Course $course): void
    {
        $userId = Auth::id();
        $enrolled = Enrollment::where('user_id', $userId)->where('course_id', $course->id)->exists();
        if (!$enrolled) {
            abort(403, 'Kamu harus mengikuti kursus terlebih dahulu.');
        }
    }

    protected function findPrev(Course $course, CourseModule $module, CourseSubmodule $submodule): ?array
    {
        $mods = $course->modules->values();
        $curMIndex = $mods->search(fn($m) => $m->id === $module->id);

        $subs = $module->submodules->values();
        $curSIndex = $subs->search(fn($s) => $s->id === $submodule->id);

        if ($curSIndex !== false && $curSIndex - 1 >= 0) {
            return ['module' => $module, 'submodule' => $subs[$curSIndex - 1]];
        }

        if ($curMIndex !== false && $curMIndex - 1 >= 0) {
            $prevMod = $mods[$curMIndex - 1];
            $prevMod->loadMissing('submodules');
            if ($prevMod->submodules->count()) {
                return ['module' => $prevMod, 'submodule' => $prevMod->submodules->last()];
            }
        }
        return null;
    }

    public function completeModule(string $slug, CourseModule $module)
    {
        $course = Course::where('slug', $slug)->with(['modules.submodules'])->firstOrFail();
        $this->ensureSameCourseByIds($course, $module);
        $this->ensureEnrolled($course);

        $subIds = $module->submodules->pluck('id');
        if ($subIds->isEmpty()) {
            return back()->with('error', 'Modul ini belum memiliki submodul.');
        }

        $done = CourseSubmoduleProgress::where('user_id', auth()->id())
            ->whereIn('submodule_id', $subIds)
            ->whereNotNull('completed_at')
            ->count();

        if ($done < $subIds->count()) {
            return back()->with('error', 'Selesaikan semua submodul terlebih dahulu.');
        }

        return back()->with('success', 'Modul ditandai selesai 🎉');
    }

    protected function isCourseCompletedForUser(Course $course, int $userId): bool
    {
        $subIds = $course->modules->flatMap->submodules->pluck('id');
        if ($subIds->count() > 0) {
            $viewedCount = CourseSubmoduleProgress::where('user_id', $userId)
                ->whereIn('submodule_id', $subIds)
                ->count();
            if ($viewedCount < $subIds->count()) return false;
        }

        if ($course->quizzes->count() > 0) {
            foreach ($course->quizzes as $qz) {
                $last = QuizAttempt::where('quiz_id', $qz->id)
                    ->where('user_id', $userId)
                    ->orderByDesc('id')
                    ->first();
                if (!$last || ($last->score ?? 0) < 75) return false;
            }
        }

        return true;
    }

    public function myCourses(Request $request)
    {
        $user = Auth::user();

        $courses = Course::query()
            ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
            ->with(['modules.submodules','quizzes'])
            ->withCount(['modules','enrollments','quizzes'])
            ->latest()
            ->paginate(12);

        $progress = [];
        foreach ($courses as $c) {
            $totalSubs = $c->modules->flatMap->submodules->count();
            $doneSubs  = 0;

            if ($totalSubs > 0) {
                $doneSubs = CourseSubmoduleProgress::where('user_id', $user->id)
                    ->whereIn('submodule_id', $c->modules->flatMap->submodules->pluck('id'))
                    ->whereNotNull('completed_at')
                    ->count();
            }

            $percent = $totalSubs ? (int) round(($doneSubs / $totalSubs) * 100) : 100;

            $passed = false;
            if ($c->quizzes->count()) {
                $passed = QuizAttempt::where('user_id', $user->id)
                    ->whereIn('quiz_id', $c->quizzes->pluck('id'))
                    ->where('score', '>=', 75)
                    ->exists();
            } else {
                $passed = ($percent === 100);
            }

            $progress[$c->id] = [
                'done'    => $doneSubs,
                'total'   => $totalSubs,
                'percent' => $percent,
                'passed'  => $passed,
            ];
        }

        return view('user.kursus.my', compact('courses','progress'));
    }

    public function downloadCertificate(string $slug)
    {
        $course = Course::with(['modules.submodules', 'quizzes'])->where('slug', $slug)->firstOrFail();
        $this->authorizeAccess($course);

        $user = Auth::user();

        abort_unless($this->isCourseCompletedForUser($course, $user->id), 403, 'Sertifikat belum tersedia. Selesaikan semua materi & kuis.');

        $data = [
            'courseTitle' => $course->judul,
            'studentName' => $user->name,
            'issuedAt'    => now(),
            'certId'      => strtoupper('SA-' . dechex($course->id) . '-' . dechex($user->id) . '-' . now()->format('Ymd')),
        ];

        $pdf = Pdf::loadView('user.kursus.certificate', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'sertifikat-' . $course->slug . '-' . $user->name . '.pdf';

        return $pdf->download($filename);
    }
}
