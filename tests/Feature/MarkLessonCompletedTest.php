<?php

use App\Models\User;
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
 
 
it('mark lesson completed successfully', function () {
    Queue::fake(); // prevent actual job from running

    [$course, $lesson] = createPublishedCourseWithLesson(true);
    
    enrollUserInCourse($this->adminUser, $course);

    $response = $this->actingAs($this->adminUser)->postJson(route('mark-lessone-complted', ['lesson' => $lesson]));

    // dd($response);

    // Assert: lesson completion successful
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Lesson completed successfully.']);

    // Lesson progress should exist
    $this->assertDatabaseHas('lesson_progress', [
        'user_id' => $this->adminUser->id,
        'lesson_id' => $lesson->id,
    ]);

    // No course completion yet since only one lesson was marked
    $this->assertDatabaseHas('course_completions', [
        'user_id' => $this->adminUser->id,
        'course_id' => $course->id,
    ]);

});



it('fails if course is unpublished', function () {
    Queue::fake();

    [$course, $lesson] = createPublishedCourseWithLesson(false);

    $response = $this->actingAs($this->adminUser)->postJson(
        route('mark-lessone-complted', ['lesson' => $lesson])
    );

    $response->assertStatus(400)
        ->assertJson(['message' => 'Course is not published']);

    assertNoLessonOrCourseProgress($this->adminUser, $course, $lesson);
});

it('fails if user not enrolled', function () {
    Queue::fake();

    [$course, $lesson] = createPublishedCourseWithLesson(true);

    $response = $this->actingAs($this->adminUser)->postJson(
        route('mark-lessone-complted', ['lesson' => $lesson])
    );

    $response->assertStatus(400)
        ->assertJson(['message' => 'User not enrolled in this course.']);

    assertNoLessonOrCourseProgress($this->adminUser, $course, $lesson);
});