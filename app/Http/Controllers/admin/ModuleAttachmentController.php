<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleAttachment;
use Illuminate\Http\Request;

class ModuleAttachmentController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $request->validate([
            'file' => 'required|file|max:51200',
        ]);

        $path = $request->file('file')->store('module_files', 'public');

        $module->attachments()->create([
            'original_name' => $request->file('file')->getClientOriginalName(),
            'file_path'     => $path,
            'size'          => round($request->file('file')->getSize()/1024),
        ]);

        return back()->with('success','File diunggah.');
    }

    public function destroy(ModuleAttachment $attachment)
    {
        \Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
        return back()->with('success','File dihapus.');
    }
}
