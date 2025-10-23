<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\CourseProgressPercentageAction;
use App\Models\Course;

class CourseProgressPercentage extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Course $course, CourseProgressPercentageAction $action)
    {
        try {
            $progress = $action($course);

            return response()->json(['message' => 'Progress returned', 'data' => $progress], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }      
    }
}
 