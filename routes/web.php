<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\TrackController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin');
    Route::get('/users', [AdminController::class, 'userList'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/artists', [App\Http\Controllers\AdminController::class, 'artists'])->name('admin.artists');
    Route::get('/admin/albums', [AlbumController::class, 'index'])->name('admin.albums');
    Route::get('/tracks', [App\Http\Controllers\Admin\TrackController::class, 'index'])->name('admin.tracks.index');
    Route::post('/tracks/fetch', [TrackController::class, 'fetchTrack'])->name('admin.tracks.fetch'); // Đảm bảo route này có mặt
    Route::get('/tracks/downloaded', [TrackController::class, 'downloadedAudios'])->name('admin.tracks.downloaded');
});

require __DIR__.'/auth.php';

Route::get('/test', function() {
    return 'Test route works!';
});

Route::get('/schedule-run', function () {
    Artisan::call('schedule:run');
    return 'Schedule run successfully';
})->name('schedule.run');
