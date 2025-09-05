<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KursusController extends Controller
{
    public function index()
    {
        $kursus = Course::withCount(['modules','enrollments','quizzes'])
            ->latest()->paginate(12);

        return view('admin.kursus.index', compact('kursus'));
    }

    public function create()
    {
        return view('admin.kursus.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'                => 'required|string|max:255',
            'slug'                 => 'nullable|string|unique:courses,slug',
            'kategori'             => 'required|string|max:100',
            'tingkat'              => 'required|string|max:100',
            'deskripsi'            => 'required|string',
            'tanggal_mulai'        => 'nullable|date',
            'durasi'               => 'nullable|string|max:100',
            'link_pendaftaran'     => 'nullable|url|max:255',
            'sertifikat_diberikan' => 'nullable|boolean',
            'kuota'                => 'nullable|integer|min:0',
            'status'               => 'nullable|in:Active,Inactive,Draft',
            'gambar'               => 'nullable|image|max:2048',

            'modules'                        => 'nullable|array',
            'modules.*.title'                => 'required_with:modules|string|max:255',
            'modules.*.summary'              => 'nullable|string',
            'modules.*.video_url'            => 'nullable|url',
            'modules.*.sort_order'           => 'nullable|integer|min:1',

            'modules.*.submodules'               => 'array',
            'modules.*.submodules.*.title'       => 'nullable|string|max:255',
            'modules.*.submodules.*.content'     => 'nullable|string',
            'modules.*.submodules.*.video_url'   => 'nullable|url',
            'modules.*.submodules.*.sort_order'  => 'nullable|integer',
            'modules.*.submodules.*.attachment'  => 'nullable|file|max:10240',

            'quiz.title'                     => 'nullable|string|max:255',
            'quiz.time_limit'                => 'nullable|integer|min:1',
            'quiz.questions'                 => 'nullable|array',
            'quiz.questions.*.question'      => 'required_with:quiz.questions|string',
            'quiz.questions.*.options'       => 'required_with:quiz.questions|array|min:2',
            'quiz.questions.*.correct'       => 'required_with:quiz.questions|integer',
            'quiz.questions.*.options.*'     => 'required_with:quiz.questions|string|max:255',
        ]);

        $data['status']               = $data['status'] ?? 'Active';
        $data['sertifikat_diberikan'] = $request->boolean('sertifikat_diberikan');

        if (empty($data['slug'])) {
            $base = Str::slug($data['judul']);
            $slug = $base;
            $i = 1;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('courses', 'public');
        }

        $course = Course::create([
            'judul'         => $data['judul'],
            'slug'          => $data['slug'],
            'deskripsi'     => $data['deskripsi'],
            'kategori'      => $data['kategori'],
            'tingkat'       => $data['tingkat'] ?? null,
            'tanggal_mulai' => $data['tanggal_mulai'] ?? null,
        ]);

        if (!empty($data['modules'])) {
            foreach ($data['modules'] as $i => $m) {
                $course->modules()->create([
                    'title'      => $m['title'],
                    'summary'    => $m['summary'] ?? null,
                    'video_url'  => $m['video_url'] ?? null,
                    'sort_order' => $m['sort_order'] ?? ($i + 1),
                ]);
            }
        }

        if (!empty($data['quiz']['title'])) {
            $quiz = $course->quizzes()->create([
                'title'      => $data['quiz']['title'],
                'time_limit' => $data['quiz']['time_limit'] ?? null,
            ]);

            if (!empty($data['quiz']['questions'])) {
                foreach ($data['quiz']['questions'] as $q) {
                    $question = $quiz->questions()->create([
                        'question' => $q['question'],
                    ]);
                    foreach ($q['options'] as $idx => $optText) {
                        $question->options()->create([
                            'text'       => $optText,
                            'is_correct' => ((int)$q['correct'] === $idx),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.kursus.index')
            ->with('success', 'Kursus beserta modul/quiz berhasil dibuat.');
    }

    public function show(Course $kursus)
    {
        $kursus->load(['modules.attachments','quizzes.questions.options'])
               ->loadCount(['enrollments','modules','quizzes']);
        return view('admin.kursus.show', compact('kursus'));
    }

    public function edit(Course $kursus)
    {
        return view('admin.kursus.edit', compact('kursus'));
    }

    public function update(Request $request, Course $kursus)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'kategori'      => 'required|string|max:100',
            'tingkat'       => 'nullable|string|max:100',
            'tanggal_mulai' => 'nullable|date',
        ]);

        $kursus->update($request->only('judul','deskripsi','kategori','tingkat','tanggal_mulai'));

        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil diperbarui.');
    }

    public function destroy(Course $kursus)
    {
        $kursus->delete();
        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil dihapus.');
    }
}
