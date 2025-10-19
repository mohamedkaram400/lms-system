<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Jobs\UserEnrolledInCourseJob;
use Illuminate\Support\Facades\Auth;
use App\Actions\EnrollUserInCourseAction;

class EnrollUserInCourse extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Course $course, EnrollUserInCourseAction $action)
    {
        // Get auth user
        $user = Auth::user();

        try {
            // Excute the action class for this enrollment
            $enrolled = $action($user, $course);
            if (!$enrolled) {
                return response()->json(['message' => 'You are already enrolled.'], 409);
            }

            // Dispatch the job after creating new enrollment
            UserEnrolledInCourseJob::dispatch($user->id, $course->id);

            // Return response after enrollment
            return response()->json(['message' => 'Enrolled successfully', 'course' => $course], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
 