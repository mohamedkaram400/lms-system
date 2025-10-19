<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Actions\MarkLessonStartedAction;

class MarkLessonStarted extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Lesson $lesson, MarkLessonStartedAction $action)
    {
        // Get auth user
        $user = Auth::user();

        try {
            // Excute the action class for this enrollment
            $enrolled = $action($user, $lesson);
            if (!$enrolled) {
                return response()->json(['message' => 'This course not enrolled.'], 409);
            }

            // Return response after enrollment
            return response()->json(['message' => 'Lesson started successfully', 'lesson' => $lesson], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
  