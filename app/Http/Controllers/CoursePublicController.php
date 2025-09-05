<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseSubmodule;
use App\Models\CourseSubmoduleProgress;
use App\Models\Enrollment;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursePublicController extends Controller
{
    public function show(string $slug)
    {
        $course = Course::with([
            'modules' => fn($q) => $q->orderBy('sort_order')->with(['submodules' => fn($q2)=>$q2->orderBy('sort_order')]),
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

        return view('kursus.show', compact(
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
            ->with('success','Berhasil mendaftar. Selamat belajar!');
    }

    public function startLearning(string $slug)
    {
        $course = Course::with(['modules.submodules'])->where('slug', $slug)->firstOrFail();

        $this->ensureEnrolled($course);

        $firstModule = $course->modules->first();
        if (!$firstModule) {
            return back()->with('error','Belum ada modul pada kursus ini.');
        }

        $firstSub = $firstModule->submodules->first();
        if ($firstSub) {
            return redirect()->route('kursus.submodules.show', [$course->slug, $firstModule->id, $firstSub->id]);
        }

        return redirect()->route('kursus.modules.show', [$course->slug, $firstModule->id]);
    }

    public function showModule(string $slug, CourseModule $module)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $this->ensureSameCourse($course, $module->course);
        $this->ensureEnrolled($course);

        $module->load('submodules');

        if ($module->submodules->count() > 0) {
            return redirect()->route('kursus.submodules.show', [$course->slug, $module->id, $module->submodules->first()->id]);
        }

        return view('kursus.learn.module', [
            'course' => $course,
            'module' => $module,
            'submodule' => null,
            'progressMap' => [],
            'nextUrl' => null,
        ]);
    }

    public function showSubmodule(string $slug, CourseModule $module, CourseSubmodule $submodule)
    {
        $course = Course::where('slug', $slug)->with(['modules.submodules'])->firstOrFail();
        $this->ensureSameCourse($course, $module->course);
        $this->ensureSameModule($module, $submodule->module);
        $this->ensureEnrolled($course);

        $course->load(['modules.submodules']);

        $progressRows = CourseSubmoduleProgress::where('user_id', Auth::id())
            ->whereIn('submodule_id', $course->modules->flatMap->submodules->pluck('id'))
            ->get()
            ->keyBy('submodule_id');

        $next = $this->findNext($course, $module, $submodule);
        $nextUrl = $next
            ? route('kursus.submodules.show', [$course->slug, $next['module']->id, $next['submodule']->id])
            : null;

        return view('kursus.learn.module', [
            'course'      => $course,
            'module'      => $module,
            'submodule'   => $submodule,
            'progressMap' => $progressRows,
            'nextUrl'     => $nextUrl,
        ]);
    }

    public function completeSubmodule(string $slug, CourseModule $module, CourseSubmodule $submodule)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $this->ensureSameCourse($course, $module->course);
        $this->ensureSameModule($module, $submodule->module);
        $this->ensureEnrolled($course);

        CourseSubmoduleProgress::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'submodule_id'=> $submodule->id,
            ],
            [
                'course_id'   => $course->id,
                'module_id'   => $module->id,
                'completed_at'=> now(),
            ]
        );

        return back()->with('success','Submodul ditandai selesai.');
    }

    public function next(string $slug, CourseModule $module, CourseSubmodule $submodule)
    {
        $course = Course::where('slug', $slug)->with(['modules.submodules'])->firstOrFail();
        $this->ensureSameCourse($course, $module->course);
        $this->ensureSameModule($module, $submodule->module);
        $this->ensureEnrolled($course);

        CourseSubmoduleProgress::updateOrCreate(
            ['user_id'=>Auth::id(), 'submodule_id'=>$submodule->id],
            ['course_id'=>$course->id, 'module_id'=>$module->id, 'completed_at'=>now()]
        );

        $next = $this->findNext($course, $module, $submodule);
        if ($next) {
            return redirect()->route('kursus.submodules.show', [$course->slug, $next['module']->id, $next['submodule']->id]);
        }
        return back()->with('success','Kamu telah menyelesaikan semua materi 🎉');
    }

    protected function ensureEnrolled(Course $course): void
    {
        if (!Auth::check() || !$course->users()->where('users.id', Auth::id())->exists()) {
            abort(403, 'Anda belum terdaftar di kursus ini.');
        }
    }

    protected function ensureSameCourse(Course $courseA, Course $courseB): void
    {
        if ($courseA->id !== $courseB->id) abort(404);
    }

    protected function ensureSameModule(CourseModule $mA, CourseModule $mB): void
    {
        if ($mA->id !== $mB->id) abort(404);
    }

    protected function findNext(Course $course, CourseModule $module, CourseSubmodule $submodule): ?array
    {
        $mods = $course->modules->values();
        $curMIndex = $mods->search(fn($m) => $m->id === $module->id);

        $subs = $module->submodules->values();
        $curSIndex = $subs->search(fn($s) => $s->id === $submodule->id);

        if ($curSIndex !== false && $curSIndex + 1 < $subs->count()) {
            return ['module'=>$module, 'submodule'=>$subs[$curSIndex+1]];
        }
        if ($curMIndex !== false && $curMIndex + 1 < $mods->count()) {
            $nextMod = $mods[$curMIndex+1];
            $nextMod->loadMissing('submodules');
            if ($nextMod->submodules->count()) {
                return ['module'=>$nextMod, 'submodule'=>$nextMod->submodules->first()];
            }
        }
        return null;
    }

    public function startQuiz(Course $course, Quiz $quiz)
    {
        $this->authorizeAccess($course);

        if ($quiz->course_id !== $course->id) { abort(404); }

        $quiz->load('questions.options');
        return view('user.kursus.quiz', compact('course','quiz'));
    }

    public function submitQuiz(Request $request, Course $course, Quiz $quiz)
    {
        $this->authorizeAccess($course);
        if ($quiz->course_id !== $course->id) { abort(404); }

        $data = $request->validate([
            'answers' => 'required|array'
        ]);

        $quiz->load('questions.options');
        $correct = 0;
        foreach ($quiz->questions as $i => $q) {
            $jawab = $data['answers'][$q->id] ?? null;
            if (!is_null($jawab) && isset($q->options[$jawab]) && $q->correct_index == $jawab) {
                $correct++;
            }
        }
        $score = round(($correct / max(1, $quiz->questions->count())) * 100);

        return back()->with('success', "Quiz dikumpulkan. Skor kamu: {$score}");
    }

    protected function authorizeAccess(Course $course): void
    {
        $userId = Auth::id();
        $enrolled = Enrollment::where('user_id',$userId)->where('course_id',$course->id)->exists();
        if (!$enrolled) {
            abort(403, 'Kamu harus mengikuti kursus terlebih dahulu.');
        }
    }
}
