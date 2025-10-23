<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin and normal user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $user = User::factory()->create([
            'name' => 'John Student',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create course and lessons
        $course = Course::factory()->create([
            'title' => 'Laravel for Beginners',
            'is_published' => true,
        ]);

        Lesson::factory()->count(5)->create([
            'course_id' => $course->id,
        ]);

        // Enroll user
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now()
        ]);
    }
}