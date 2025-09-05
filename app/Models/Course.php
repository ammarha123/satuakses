<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'tingkat',
        'deskripsi',
        'tanggal_mulai',
        'durasi',
        'link_pendaftaran',
        'sertifikat_diberikan',
        'kuota',
        'status',
        'gambar',
    ];

    protected $casts = [
        'tanggal_mulai'        => 'date',
        'sertifikat_diberikan' => 'boolean',
        'kuota'                => 'integer',
    ];

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments')->withTimestamps();
    }

    protected static function booted()
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $base = Str::slug($course->judul);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $course->slug = $slug;
            }
        });
    }

    // Route model binding by slug (opsional, kalau mau pakai {course:slug})
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
