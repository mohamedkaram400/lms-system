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
            $action($user, $lesson);

            return response()->json(['message' => 'Lesson started successfully', 'lesson' => $lesson], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
  