<?php

namespace App\Actions;

use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class UpdateLessonProgressAction
{
    public function __invoke($data, Lesson $lesson)
    {
        $course = $lesson->course;

        if (!$course->is_published) {
            throw new \Exception('Course is not published');
        }

        $isEnrolled = Enrollment::where('course_id', $course->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isEnrolled) {
            return false;
        }

        $progress = LessonProgress::where('lesson_id', $lesson->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$progress) {
            throw new \Exception('You must start the lesson before updating progress');
        }

         $progress->update([
            'watch_seconds' => $data['watch_seconds']
        ]);

        return true;  
    }
}
 