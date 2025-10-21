<?php
namespace App\Validators;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;

class CourseAccessValidator
{
    /**
     * Validate that a user can access the given lesson's course.
     */
    public function validate(User $user, Course $course)
    {
        if (!$course->is_published) {
            throw new \Exception('Course is not published');
        }

        $enrolled = Enrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$enrolled) {
            throw new \Exception('User not enrolled in this course.');
        }
    }
    
}