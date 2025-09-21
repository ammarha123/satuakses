<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id','name','slug','email','phone','website',
        'province','city','address','logo_path','description','status',
    ];

    public function user() { return $this->belongsTo(User::class); }

     public function lowongans()
    {
        return $this->hasMany(Lowongan::class, 'kategori_id');
    }
}
