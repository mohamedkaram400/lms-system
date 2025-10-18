<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $fillable = ['user_id', 'lesson_id', 'started_at', 'completed_at', 'watch_seconds'];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function lesson() 
    {
        return $this->belongsTo(Lesson::class);
    }
}
