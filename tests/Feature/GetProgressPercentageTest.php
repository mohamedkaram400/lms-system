<?php

use App\Models\User;
use App\Models\Lesson;
use App\Models\LessonProgress;

beforeEach(function () {
    $this->adminUser = User::where('email', 'admin@gmail.com')->first();

    if (! $this->adminUser) {
        $this->adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
    }
});

it('return progress percentage', function () {

    [$course, $lesson1] = createPublishedCourseWithLesson(true);

    $lesson2 = Lesson::factory()->create(['course_id' => $course->id]);
    $lesson3 = Lesson::factory()->create(['course_id' => $course->id]);
    $lesson4 = Lesson::factory()->create(['course_id' => $course->id]);

    enrollUserInCourse($this->adminUser, $course);

    // Mark two lessons as completed
    LessonProgress::factory()->create([
        'lesson_id' => $lesson1->id,
        'user_id' => $this->adminUser->id,
        'completed_at' => now(),
    ]);

    LessonProgress::factory()->create([
        'lesson_id' => $lesson3->id,
        'user_id' => $this->adminUser->id,
        'completed_at' => now(),
    ]);
    
    $response = $this->actingAs($this->adminUser)->postJson(route('progress-percentage', ['course' => $course]));

    // dd($response);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Progress returned',
        'data' => 50.0, // because 2/4 lessons completed
    ]);
});


it('does not enroll if course is unpublished', function () {

    [$course, $lesson] = createPublishedCourseWithLesson(false);

    $response = $this->actingAs($this->adminUser)->postJson(route('start-lesson', ['lesson' => $lesson->id]));

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});


it('this course not enrolled', function () {

    [$course, $lesson] = createPublishedCourseWithLesson(true);

    $response = $this->actingAs($this->adminUser)->postJson(route('start-lesson', ['lesson' => $lesson->id]));

    // dd($response);

    $response->assertStatus(400);
    $response->assertJson(['message' => 'User not enrolled in this course.']);
});
