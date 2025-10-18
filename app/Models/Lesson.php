<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'order', 'video_url', 'duration_seconds', 'is_free_preview'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessonProgress() 
    {
        return $this->hasOne(LessonProgress::class);
    }
}
