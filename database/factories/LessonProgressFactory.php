<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LessonProgress>
 */
class LessonProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'lesson_id'     => Lesson::factory(),
            'user_id'       => User::factory(),
            'started_at'    => now(),
            'completed_at'  => null, 
            'watch_seconds' => 30,
        ];
    }
}