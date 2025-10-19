<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Actions\UpdateLessonProgressAction;

class UpdateLessonProgress extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Lesson $lesson, UpdateLessonProgressAction $action)
    {
        $data = $request->validate([
            'watch_seconds' => 'required|integer|min:0',
        ]);

        try {
            // Excute the action class for this enrollment
            $enrolled = $action($data, $lesson);
            if (!$enrolled) {
                return response()->json(['message' => 'This course not enrolled.'], 409);
            }

            // Return response after enrollment
            return response()->json(['message' => 'Progress updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
 