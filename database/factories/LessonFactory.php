<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'course_id' => Course::factory()->create(),
            'order' => $this->faker->numberBetween(1, 40),
            'video_url' => $this->faker->url(),
            'duration_seconds' => $this->faker->numberBetween(300, 500),
            'is_free_preview' => $this->faker->boolean(),
        ];
    }
}
