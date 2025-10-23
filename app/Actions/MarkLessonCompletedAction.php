<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Jobs\CourseCompletedJob;
use App\Models\CourseCompletion;
use Illuminate\Support\Facades\DB;
use App\Validators\CourseAccessValidator;

class MarkLessonCompletedAction
{
    public function __construct(private CourseAccessValidator $validator) {}
    public function __invoke(User $user, Lesson $lesson)
    {
        $this->validator->validate($user, $lesson->course);
        
        $course = $lesson->course;

        // ✅ Mark this lesson as completed
        LessonProgress::updateOrCreate(
            [ 
                'lesson_id' => $lesson->id,
                'user_id'   => $user->id,
            ],
            [
                'started_at' => now(),       
                'completed_at' => now(),
            ]
        );

        // ✅ Check if all lessons are completed
        $lessonIds = Lesson::where('course_id', $course->id)->pluck('id');
        $totalLessons = $lessonIds->count();

        // Get completed lessons 
        $completedLessons = LessonProgress::whereIn('lesson_id', $lessonIds)->whereNotNull('completed_at')->count();


        if ($completedLessons > 0 && $totalLessons == $completedLessons) {

            // 🎉 All lessons done → mark course as completed
                DB::transaction(function () use ($user, $course) {
                    CourseCompletion::updateOrCreate(
                        [
                            'course_id' => $course->id,
                            'user_id' => $user->id,
                        ],
                        [
                            'completed_at' => now(),
                        ]
                    );
                });


            // Dispatch the job after creating new enrollment
            CourseCompletedJob::dispatch($user->id, $course->id);
        }

        return true;
    }
} 
