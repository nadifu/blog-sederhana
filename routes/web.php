<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama → daftar post
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Dashboard → arahkan ke daftar post
Route::get('/dashboard', function () {
    return redirect()->route('posts.index');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route yang butuh login
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD posts (kecuali index & show yang bebas)
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

// Post yang boleh dibuka tanpa login
Route::resource('posts', PostController::class)->only(['index', 'show']);


// Auth dari Breeze
require __DIR__.'/auth.php';
