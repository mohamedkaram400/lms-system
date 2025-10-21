<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;
use App\Validators\CourseAccessValidator;

class MarkLessonStartedAction
{
    public function __construct(private CourseAccessValidator $validator) {}
    public function __invoke(User $user, Lesson $lesson)
    {
        $this->validator->validate($user, $lesson->course);
        
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
 