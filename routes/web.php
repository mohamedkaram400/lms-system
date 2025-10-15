<?php

use Livewire\Volt\Volt;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/test-mail', function () {
    Mail::raw('This is a test message from Laravel to Mailtrap 🎯', function ($message) {
        $message->to('your_email@mailtrap.io')
                ->subject('Test Email from Laravel');
    });

    return '✅ Test email sent (check your Mailtrap inbox)';
});
        // Route::get('level', 'settings.appearance')->name('appearance.edit');
        // Route::get('course', 'settings.appearance')->name('appearance.edit');

    // Level Routes
    // Route::controller(LevelController)->group(function () {

    // });
    
    // Course Routes

    // Lesson Routes

    // Enrollment Routes 

    // LessonProgress Routes

    // CourseCompletion Routes

});




// Auth Routes
require __DIR__.'/auth.php';
