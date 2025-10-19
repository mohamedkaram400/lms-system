<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
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
            'slug' => Str::slug($title),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'category_id' => Category::factory()->create(),
            'created_by' => User::factory(), 
            'price' => $this->faker->numberBetween(50, 500),
            'is_published' => $this->faker->boolean(),
            'language' => $this->faker->randomElement(['en', 'ar', 'fr']),
            'duration' => $this->faker->numberBetween(30, 300), // minutes
            'description' => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->sentences(2, true),
        ];
    }
}
