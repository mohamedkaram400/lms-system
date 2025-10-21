<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Course;
use App\Mail\CourseCompletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CourseCompletedJob implements ShouldQueue
{
    use Queueable;

    public int $userId;
    public int $courseId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $courseId)
    {
        $this->userId = $userId;
        $this->courseId = $courseId;
        $this->queue = "course-emails";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->userId);
        $course = Course::findOrFail($this->courseId);

        // send notification or email
        Mail::to($user->email)->send(new CourseCompletedMail($user, $course));   
    }
}
