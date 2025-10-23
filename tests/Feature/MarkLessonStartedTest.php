<?php

use App\Models\User;

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

    [$course, $lesson] = createPublishedCourseWithLesson(true);

    enrollUserInCourse($this->adminUser, $course);
    
    $response = $this->actingAs($this->adminUser)->postJson(route('start-lesson', ['lesson' => $lesson->id]));

    // dd($response);
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Lesson started successfully']);
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
