<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Course $course)
    {
        $quizzes = $course->quizzes()->withCount('questions')->get();
        return view('admin.quizzes.index', compact('course','quizzes'));
    }

    public function create(Course $course)
    {
        return view('admin.quizzes.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'time_limit' => 'nullable|integer',
        ]);
        $quiz = $course->quizzes()->create($data);
        return redirect()->route('admin.quizzes.edit', $quiz)->with('success','Quiz dibuat. Tambahkan soal.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('course','questions.options');
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('course','questions.options');
        return view('admin.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
 
        $quiz->update($request->validate([
            'title'=>'required|string|max:255',
            'time_limit'=>'nullable|integer',
        ]));

        if ($request->filled('questions')) {
            $quiz->questions()->delete();
            foreach ($request->input('questions', []) as $q) {
                $question = $quiz->questions()->create([
                    'question' => $q['question'] ?? '',
                    'correct_index' => (int)($q['correct'] ?? 0),
                ]);
                foreach ($q['options'] ?? [] as $opt) {
                    $question->options()->create(['text'=>$opt ?? '']);
                }
            }
        }

        return redirect()->route('admin.quizzes.show', $quiz)->with('success','Quiz diperbarui.');
    }

    public function destroy(Quiz $quiz)
    {
        $course = $quiz->course;
        $quiz->delete();
        return redirect()->route('admin.courses.quizzes.index', $course)->with('success','Quiz dihapus.');
    }
}

