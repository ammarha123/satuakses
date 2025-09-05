<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleAttachment extends Model
{
    protected $fillable = ['module_id','original_name','file_path','size'];

    public function module(){ return $this->belongsTo(CourseModule::class, 'module_id'); }
}