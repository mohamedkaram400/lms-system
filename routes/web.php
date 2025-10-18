<?php

use Livewire\Volt\Volt;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkLessonStarted;
use App\Http\Controllers\EnrollUserInCourse;
use App\Http\Controllers\UpdateLessonProgress;

Route::get('/home', function () {
    return view('welcome');
})->name('home');

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    
    // Setting Routes
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');


    // Enroll user course
    Route::post('enroll-course/{course_id}', EnrollUserInCourse::class)->name('enroll-course');

    // Start lesson
    Route::post('/start-lesson/{lesson_id}', MarkLessonStarted::class)->name('start-lesson');

    // Update watch seconds for played lesson
    Route::post('/lesson/{lesson}/progress', UpdateLessonProgress::class)->name('update-lesson-progress');






    // Route::get('/test-mail', function () {
    //     Mail::raw('This is a test message from Laravel to Mailtrap 🎯', function ($message) {
    //         $message->to('your_email@mailtrap.io')
    //                 ->subject('Test Email from Laravel');
    //     });

    //     return '✅ Test email sent (check your Mailtrap inbox)';
    // });

});




// Auth Routes
require __DIR__.'/auth.php';
