<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;

it('mark lessone started successfully', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create([
        'is_published' => true,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    Enrollment::factory()->create([
        'user_id'       => $user->id, 
        'course_id'     => $course->id
    ]);
    
    $response = $this->actingAs($user)->postJson(route('start-lesson', ['lesson_id' => $lesson->id]));

    // dd($response);
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Lesson started successfully']);
});


it('does not enroll if course is unpublished', function () { 

    $user = User::factory()->create();
    $course = Course::factory()->create([
        'is_published' => false,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    $response = $this->actingAs($user)->postJson(route('start-lesson', ['lesson_id' => $lesson->id]));

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});


it('this course not enrolled', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create([
        'is_published' => true,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    $response = $this->actingAs($user)->postJson(route('start-lesson', ['lesson_id' => $lesson->id]));

    // dd($response);

    $response->assertStatus(409);
    $response->assertJson(['message' => 'This course not enrolled.']);
});
