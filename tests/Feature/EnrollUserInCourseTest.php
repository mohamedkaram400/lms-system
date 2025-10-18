<?php

use App\Models\User;
use App\Models\Course;
use App\Jobs\UserEnrolledInCourse;
use Illuminate\Support\Facades\Queue;

it('enrolls a user in a course successfully', function () {
    Queue::fake(); // prevent actual job from running

    $user = User::factory()->create();
    $course = Course::factory()->create([
        'is_published' => true,
    ]);

    // Send request to your enroll route
    $response = $this->actingAs($user)->postJson(route('enroll-course', ['course_id' => $course->id]));

    // dd($response);

    $response->assertStatus(201);
    $response->assertJson(['message' => 'Enrolled successfully']);

    $this->assertDatabaseHas('enrollments', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);

    Queue::assertPushed(UserEnrolledInCourse::class);
});

it('does not enroll if course is unpublished', function () { 
    Queue::fake(); // prevent actual job from running

    $user = User::factory()->create();
    $course = Course::factory()->create([
        'is_published' => false,
    ]);

    $response = $this->actingAs($user)->postJson(route('enroll-course', ['course_id' => $course->id]));

    $response->assertStatus(400);
    $response->assertJson(['message' => 'Course is not published']);
});

it('does not enroll if user already enrolled', function () { 
    Queue::fake(); // prevent actual job from running

    $user = User::factory()->create();
    $course = Course::factory()->create([
        'is_published' => true,
    ]);

    // First enrollment
    $this->actingAs($user)->postJson(route('enroll-course', ['course_id' => $course->id]));

    // Second attempt
    $response = $this->actingAs($user)->postJson(route('enroll-course', ['course_id' => $course->id]));

    Queue::assertPushed(UserEnrolledInCourse::class, function ($job) use ($user, $course) {
        return $job->userId === $user->id && $job->courseId === $course->id;
    });
    $response->assertStatus(409);
    $response->assertJson(['message' => 'You are already enrolled.']);
});