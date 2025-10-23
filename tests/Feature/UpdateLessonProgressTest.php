<?php

use App\Models\User;
use App\Models\LessonProgress;

beforeEach(function () {
    $this->adminUser = User::where('email', 'admin@gmail.com')->first();

    if (!$this->adminUser) {
        $this->adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
    }
});

it('updates lesson progress successfully when the user is enrolled', function () {

    [$course, $lesson] = createPublishedCourseWithLesson(true);

    enrollUserInCourse($this->adminUser, $course);

    LessonProgress::factory()->create([
        'lesson_id' => $lesson->id,
        'user_id' => $this->adminUser->id,
        'watch_seconds' => 0
    ]);

    $response = $this->actingAs($this->adminUser)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );


    $response->assertStatus(200)
        ->assertJson(['message' => 'Progress updated']);
});
 

it('fails to update lesson progress when the user is not enrolled', function () {

    [$course, $lesson] = createPublishedCourseWithLesson(true);


    LessonProgress::factory()->create([
        'lesson_id' => $lesson->id,
        'user_id' => $this->adminUser->id,
        'watch_seconds' => 0
    ]);

    $response = $this->actingAs($this->adminUser)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );

    // dd($response);

    $response->assertStatus(400)
        ->assertJson(['message' => 'User not enrolled in this course.']);
});

it('returns 400 when the course is unpublished', function () {

    [$course, $lesson] = createPublishedCourseWithLesson(false);

    $response = $this->actingAs($this->adminUser)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});

it('fails to update lesson progress when the lesson has not been started', function () {

    [$course, $lesson] = createPublishedCourseWithLesson(true);

    enrollUserInCourse($this->adminUser, $course);

    $response = $this->actingAs($this->adminUser)->postJson(
        route('update-lesson-progress', ['lesson' => $lesson->id]),
        ['watch_seconds' => 50]
    );

    // dd($response);

    $response->assertStatus(400)
        ->assertJson(['message' => 'You must start the lesson before updating progress']);
});