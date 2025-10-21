<?php

use App\Models\User;
use App\Models\Course;
use App\Jobs\UserEnrolledInCourseJob;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->adminUser = User::where('email', 'admin@gmail.com')->first();

    if (!$this->adminUser) {
        $this->adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
    }
});

it('enrolls a user in a course successfully', function () {
    Queue::fake(); // prevent actual job from running

    $course = Course::factory()->create([
        'is_published' => true,
    ]);

    // Send request to your enroll route
    $response = $this->actingAs($this->adminUser)->postJson(route('enroll-course', ['course' => $course->id]));

    // dd($response);

    $response->assertStatus(201);
    $response->assertJson(['message' => 'Enrolled successfully']);

    $this->assertDatabaseHas('enrollments', [
        'user_id' => $this->adminUser->id,
        'course_id' => $course->id,
    ]);

    Queue::assertPushed(UserEnrolledInCourseJob::class);
});

it('does not enroll if course is unpublished', function () { 
    Queue::fake(); // prevent actual job from running

    $course = Course::factory()->create([
        'is_published' => false,
    ]);

    $response = $this->actingAs($this->adminUser)->postJson(route('enroll-course', ['course' => $course->id]));

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});

it('does not enroll if user already enrolled', function () { 
    Queue::fake(); // prevent actual job from running

    $course = Course::factory()->create([
        'is_published' => true,
    ]);

    // First enrollment
    $this->actingAs($this->adminUser)->postJson(route('enroll-course', ['course' => $course->id]));

    // Second attempt
    $response = $this->actingAs($this->adminUser)->postJson(route('enroll-course', ['course' => $course->id]));

    // dd($response);

    $response->assertStatus(400);
    $response->assertJson(['message' => 'User already enrolled in this course.']);
});