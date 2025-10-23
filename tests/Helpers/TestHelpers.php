<?php

use App\Models\{Course, Lesson, Enrollment, User};

if (! function_exists('createPublishedCourseWithLesson')) {
    function createPublishedCourseWithLesson($isPublished = true): array
    {
        $course = Course::factory()->create([
            'is_published' => $isPublished,
        ]);

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
        ]);

        return [$course, $lesson];
    }
}

if (! function_exists('enrollUserInCourse')) {
    function enrollUserInCourse(User $user, Course $course): void
    {
        Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }
}


if (! function_exists('assertNoLessonOrCourseProgress')) {
    function assertNoLessonOrCourseProgress(User $user, Course $course, Lesson $lesson): void
    {
        test()->assertDatabaseMissing('lesson_progress', [
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
        ]);

        test()->assertDatabaseMissing('course_completions', [
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);
    }
}