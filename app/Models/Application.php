<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id','lowongan_id','company_id','cover_letter','cv_path','status','submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function lowongan() { return $this->belongsTo(Lowongan::class); }
    public function company()  { return $this->belongsTo(Company::class); }
}
