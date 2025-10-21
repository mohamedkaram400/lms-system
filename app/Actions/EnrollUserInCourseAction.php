<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use App\Jobs\UserEnrolledInCourseJob;

class EnrollUserInCourseAction
{
    public function __invoke(User $user, Course $course)
    {
        if (!$course->is_published) {
            throw new \Exception('Course is not published');
        }

        // ✅ Check if already enrolled
        $alreadyEnrolled = Enrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyEnrolled) {
            throw new \Exception('User already enrolled in this course.');
        }
        
        DB::transaction(function () use ($user, $course) {
            Enrollment::create([
                'course_id' => $course->id,
                'user_id' => $user->id,
                'enrolled_at' => now(),
            ]);
        });


        // Dispatch the job after creating new enrollment
        UserEnrolledInCourseJob::dispatch($user->id, $course->id);

        return true;
    }
}
