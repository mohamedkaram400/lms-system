<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Actions\MarkLessonCompletedAction;

class MarkLessonCompleted extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, MarkLessonCompletedAction $action)
    {
        // Get auth user
        $user = Auth::user();

        // Get the selected course
        $course = Course::findOrFail($request->course_id);

        try {
            // Excute the action class for this enrollment
            $enrolled = $action($user, $course);
            if (!$enrolled) {
                return response()->json(['message' => 'You are already enrolled.'], 409);
            }

            // Return response after enrollment
            return response()->json(['message' => 'Enrolled successfully', 'course' => $course], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
 