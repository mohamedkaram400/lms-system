<?php

namespace App\Actions;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use App\Validators\CourseAccessValidator;

class UpdateLessonProgressAction
{
    public function __construct(private CourseAccessValidator $validator) {}
    
    public function __invoke($data, Lesson $lesson)
    {
        $this->validator->validate(Auth::user(), $lesson->course);

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
 