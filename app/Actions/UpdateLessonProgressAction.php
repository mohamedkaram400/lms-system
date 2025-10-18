<?php

namespace App\Actions;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class UpdateLessonProgressAction
{
    public function __invoke($data, Lesson $lesson)
    {
        LessonProgress::updateOrCreate(
            ['lesson_id' => $lesson->id, 'user_id' => Auth::id()],
            ['watch_seconds' => $data['watch_seconds']]
        );

        return true;  
    }
}
