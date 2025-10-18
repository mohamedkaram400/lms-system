<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;

class MarkLessonStartedAction
{
    public function __invoke(User $user, Lesson $lesson)
    {
        $course = Course::where('id', $lesson->course_id)->first();

        if (!$course->is_published) {
            throw new \Exception('Course is not published');
        }

        $isEnrolled = Enrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isEnrolled) {
            return false;
        }

        $existingProgress = LessonProgress::where('lesson_id', $lesson->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$existingProgress) {
            DB::transaction(function () use ($user, $lesson) {
                LessonProgress::create([
                    'lesson_id'     => $lesson->id,
                    'user_id'       => $user->id,
                    'started_at'    => now(),
                    'completed_at'  => null,
                    'watch_seconds' => 0,
                ]);
            });
        }

        return true;
    }
}
 