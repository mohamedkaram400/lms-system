<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class EnrollUserInCourseAction
{
    public function __invoke(User $user, Course $course)
    {
        if (!$course->is_published) {
            throw new \Exception('Course is not published');
        }

        $already = Enrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($already) {
            return false;
        }

        DB::transaction(function () use ($user, $course) {
            Enrollment::create([
                'course_id' => $course->id,
                'user_id' => $user->id,
                'enrolled_at' => now(),
            ]);
        });

        return true;
    }
}
