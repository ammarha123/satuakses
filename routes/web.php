<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\KursusController;
use App\Http\Controllers\Admin\LowonganController as AdminLowonganController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Employer\ApplicationController;
use App\Http\Controllers\Employer\LowonganController as EmployerLowonganController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\LowonganPublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\User\CoursePublicController;
use App\Http\Controllers\User\LamaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('index');
Route::get('/', [LowonganController::class, 'index'])->name('index');
Route::get('/', [CourseController::class, 'index'])->name('index');
Route::get('/lowongan', [LowonganPublicController::class, 'index'])->name('lowongan.index');
Route::get('/kursus',   [CourseController::class, 'index'])->name('course.public.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile',        [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',      [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile',     [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    // Route::get('/my-courses', [CourseController::class, 'index'])->name('user.courses');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/lowongan', [LowonganPublicController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/{slug}', [LowonganPublicController::class, 'show'])
        ->name('lowongan.detail');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('kursus', KursusController::class)
        ->parameters(['kursus' => 'kursus']);
    Route::resource('courses.modules', ModuleController::class)->shallow();
    Route::resource('courses.quizzes', QuizController::class)->shallow();

    Route::resource('company', CompanyController::class)->only(['index', 'create', 'store']);
    Route::get('company/create-success/{company}', [CompanyController::class, 'createSuccess'])
        ->name('company.create_success');
    Route::post('modules/{module}/submodules', [ModuleController::class, 'storeSubmodule'])
        ->name('modules.submodules.store');                 // ← nama tanpa 'admin.' ganda
    Route::get('submodules/{submodule}/edit', [ModuleController::class, 'editSubmodule'])
        ->name('submodules.edit');
    Route::put('submodules/{submodule}', [ModuleController::class, 'updateSubmodule'])
        ->name('submodules.update');
    Route::delete('submodules/{submodule}', [ModuleController::class, 'destroySubmodule'])
        ->name('submodules.destroy');
});


Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/', fn() => view('employer.index'))->name('dashboard');
    Route::resource('lowongan', EmployerLowonganController::class);
    Route::get('/applications', [ApplicationController::class, 'index'])
        ->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])
        ->name('applications.show');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])
        ->name('applications.updateStatus'); // optional: ubah status
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', fn() => view('user.index'))->name('dashboard');
    Route::get('/lamaran-saya', [LamaranController::class, 'index'])
        ->name('lamaran.index');
    Route::delete('/lamaran-saya/{application}', [LamaranController::class, 'destroy'])
        ->name('lamaran.destroy');
    Route::post('/lowongan/{lowongan}/apply', [LamaranController::class, 'store'])
        ->name('apply');
    Route::get('/my-courses', [CoursePublicController::class, 'myCourses'])
        ->name('mycourses.index');
});


Route::get('/kursus', [CoursePublicController::class, 'index'])->name('kursus.index');

Route::prefix('kursus')->group(function () {
    Route::get('/{slug}', [CoursePublicController::class, 'show'])->name('kursus.show');

    Route::middleware('auth')->group(function () {
        Route::post('/{slug}/enroll', [CoursePublicController::class, 'enroll'])
            ->name('kursus.enroll');

        // belajar
        Route::get('/{slug}/modules/{module}', [CoursePublicController::class, 'showModule'])
            ->whereNumber('module')->name('kursus.modules.show');

        Route::get('/{slug}/modules/{module}/submodules/{submodule}', [CoursePublicController::class, 'showSubmodule'])
            ->whereNumber('module')->whereNumber('submodule')
            ->name('kursus.submodules.show');

        Route::post('/{slug}/modules/{module}/submodules/{submodule}/complete', [CoursePublicController::class, 'completeSubmodule'])
            ->whereNumber('module')->whereNumber('submodule')
            ->name('kursus.submodules.complete');

         Route::post('/{slug}/modules/{module}/complete', [CoursePublicController::class, 'completeModule'])
            ->name('kursus.modules.complete');

        Route::post('/{slug}/modules/{module}/submodules/{submodule}/next', [CoursePublicController::class, 'next'])
            ->whereNumber('module')->whereNumber('submodule')
            ->name('kursus.submodules.next');

        Route::post('/{slug}/modules/{module}/submodules/{submodule}/prev', [CoursePublicController::class, 'prev'])
            ->whereNumber('module')->whereNumber('submodule')
            ->name('kursus.submodules.prev');

       Route::get ('/{slug}/quiz/{quiz}',                [CoursePublicController::class, 'startQuiz'])->name('kursus.quiz.start');
        Route::post('/{slug}/quiz/{quiz}',                [CoursePublicController::class, 'submitQuiz'])->name('kursus.quiz.submit');
        Route::get ('/{slug}/quiz/{quiz}/result',         [CoursePublicController::class, 'resultQuiz'])->name('kursus.quiz.result'); // ⬅️ baru
        Route::get('/{slug}/certificate', [CoursePublicController::class, 'downloadCertificate'])
            ->name('kursus.certificate.download');
    });
});


require __DIR__ . '/auth.php';
