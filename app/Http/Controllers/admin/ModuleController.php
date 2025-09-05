<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseSubmodule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function index(Course $course)
    {
        $modules = $course->modules()
            ->withCount('submodules')
            ->orderBy('sort_order')
            ->get();

        return view('admin.modules.index', compact('course', 'modules'));
    }

    public function create(Course $course)
    {
        return view('admin.modules.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'video_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);
        $course->modules()->create($data);
        return redirect()->route('admin.courses.modules.index', $course)->with('success', 'Modul ditambahkan.');
    }

    public function show(CourseModule $module)
    {
        $module->load('course', 'submodules');
        return view('admin.modules.show', compact('module'));
    }

    public function edit(CourseModule $module)
    {
        $module->load('course');
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, CourseModule $module)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'video_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);
        $module->update($data);
        return redirect()->route('admin.modules.show', $module)->with('success', 'Modul diperbarui.');
    }

    public function destroy(CourseModule $module)
    {
        $course = $module->course;
        $module->delete();
        return redirect()->route('admin.courses.modules.index', $course)->with('success', 'Modul dihapus.');
    }

    // ---------- SUBMODULES ----------
    public function storeSubmodule(Request $request, CourseModule $module)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'video_url'   => 'nullable|url',
            'sort_order'  => 'nullable|integer',
            'attachment'  => 'nullable|file|max:10240',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('course/submodules', 'public');
        }
        $module->submodules()->create($data);
        return back()->with('success', 'Submodul ditambahkan.');
    }

    public function editSubmodule(CourseSubmodule $submodule)
    {
        $submodule->load('module.course');
        return view('admin.modules.submodule_edit', compact('submodule'));
    }

    public function updateSubmodule(Request $request, CourseSubmodule $submodule)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'video_url'   => 'nullable|url',
            'sort_order'  => 'nullable|integer',
            'attachment'  => 'nullable|file|max:10240',
        ]);
        if ($request->hasFile('attachment')) {
            if ($submodule->attachment_path) Storage::disk('public')->delete($submodule->attachment_path);
            $data['attachment_path'] = $request->file('attachment')->store('course/submodules', 'public');
        }
        $submodule->update($data);
        return redirect()->route('admin.modules.show', $submodule->module)->with('success', 'Submodul diperbarui.');
    }

    public function destroySubmodule(CourseSubmodule $submodule)
    {
        $module = $submodule->module;
        if ($submodule->attachment_path) Storage::disk('public')->delete($submodule->attachment_path);
        $submodule->delete();
        return redirect()->route('admin.modules.show', $module)->with('success', 'Submodul dihapus.');
    }
}
