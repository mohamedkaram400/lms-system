<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Actions\MarkLessonCompletedAction;

class MarkLessonCompleted extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Lesson $lesson, MarkLessonCompletedAction $action)
    {
        // Get auth user
        $user = Auth::user();

        try {
            $action($user, $lesson);

            return response()->json(['message' => 'Lesson completed successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
   