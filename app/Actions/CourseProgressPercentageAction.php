<?php
namespace App\Actions;

use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use App\Validators\CourseAccessValidator;

class CourseProgressPercentageAction
{
    public function __construct(private CourseAccessValidator $validator) {}
    public function __invoke(Course $course): float|int
    {
        $this->validator->validate(Auth::user(), $course);

        $total = $course->lessons()->count();

        $completed = LessonProgress::query()
                    ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                    ->where('user_id', Auth::id())
                    ->whereNotNull('comleted_at')
                    ->count();
        
        return $total > 0 ? ($completed / $total) * 100 : 0;
    }
}