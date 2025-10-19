<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;

beforeEach(function () {
    $this->adminUser = User::where('email', 'admin@gmail.com')->first();

    if (!$this->adminUser) {
        $this->adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
    }
});

it('mark lessone started successfully', function () {
    $course = Course::factory()->create([
        'is_published' => true,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    Enrollment::factory()->create([
        'user_id'       => $this->adminUser->id, 
        'course_id'     => $course->id
    ]);
    
    $response = $this->actingAs($this->adminUser)->postJson(route('start-lesson', ['lesson_id' => $lesson->id]));

    // dd($response);
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Lesson started successfully']);
});


it('does not enroll if course is unpublished', function () { 

    $course = Course::factory()->create([
        'is_published' => false,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    $response = $this->actingAs($this->adminUser)->postJson(route('start-lesson', ['lesson_id' => $lesson->id]));

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});


it('this course not enrolled', function () {
    $course = Course::factory()->create([
        'is_published' => true,
    ]);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
    ]);

    $response = $this->actingAs($this->adminUser)->postJson(route('start-lesson', ['lesson_id' => $lesson->id]));

    // dd($response);

    $response->assertStatus(409);
    $response->assertJson(['message' => 'This course not enrolled.']);
});
