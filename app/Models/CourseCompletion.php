<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCompletion extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'course_id', 'completed_at'];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function course() 
    {
        return $this->belongsTo(Course::class);
    }
}
