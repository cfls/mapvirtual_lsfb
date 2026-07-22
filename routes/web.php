<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');
Route::view('/', 'map');
Route::view('/login', 'admin.login')
    ->middleware('guest')
    ->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::view('/lieux', 'admin.landmarks')->name('admin.landmarks');
});