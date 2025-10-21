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
            $action($data, $lesson);

            return response()->json(['message' => 'Progress updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
 