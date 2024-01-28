<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('posts', PostController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth']);

Route::get('posts/my_posts', [PostController::class, 'my_posts'])
    ->name('posts.my_posts')
    ->middleware(['auth']);

Route::get('posts/{post}/archive}', [PostController::class, 'archive'])
    ->name('posts.archive')
    ->middleware(['auth']);

Route::get('posts/{post}/publish}', [PostController::class, 'archive'])
    ->name('posts.publish')
    ->middleware(['auth']);

Route::post('posts/{post}/comment}', [PostController::class, 'comment'])
    ->name('posts.comment')
    ->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
