<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;

beforeEach(function () {
    $this->admin = User::where('email', 'admin@gmail.com')->first();

    if (!$this->admin) {
        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
    }
});

it('updates lesson progress successfully when the user is enrolled', function () {

    $course = Course::factory()->create(['is_published' => true]);

    $lesson = Lesson::factory()->create([
        'course_id' => $course->id
    ]);

    Enrollment::factory()->create([
        'course_id' => $course->id,
        'user_id' => $this->admin->id,
    ]);

    LessonProgress::factory()->create([
        'lesson_id' => $lesson->id,
        'user_id' => $this->admin->id,
        'watch_seconds' => 0
    ]);

    $response = $this->actingAs($this->admin)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );


    $response->assertStatus(200)
        ->assertJson(['message' => 'Progress updated']);
});
 

it('fails to update lesson progress when the user is not enrolled', function () {

    $course = Course::factory()->create(['is_published' => true]);

    $lesson = Lesson::factory()->create([
        'course_id' => $course->id
    ]);

    LessonProgress::factory()->create([
        'lesson_id' => $lesson->id,
        'user_id' => $this->admin->id,
        'watch_seconds' => 0
    ]);

    $response = $this->actingAs($this->admin)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );

    // dd($response);

    $response->assertStatus(400)
        ->assertJson(['message' => 'User not enrolled in this course.']);
});

it('returns 400 when the course is unpublished', function () {

    $course = Course::factory()->create([
        'is_published' => false,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    $response = $this->actingAs($this->admin)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});

it('fails to update lesson progress when the lesson has not been started', function () {

    $course = Course::factory()->create(['is_published' => true]);

    $lesson = Lesson::factory()->create([
        'course_id' => $course->id
    ]);

    Enrollment::factory()->create([
        'course_id' => $course->id,
        'user_id' => $this->admin->id,
    ]);

    $response = $this->actingAs($this->admin)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );

    // dd($response);

    $response->assertStatus(400)
        ->assertJson(['message' => 'You must start the lesson before updating progress']);
});