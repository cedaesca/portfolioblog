<?php

use App\Http\Controllers\PostController;
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

Route::get('/', [PostController::class, 'index'])->name('index');

Route::controller(PostController::class)->group(function () {
    $slugRegex = '^[a-z0-9]+(-[a-z0-9]+)*$';

    Route::get('/posts/create', 'create')->name('posts.create');
    Route::post('/posts', 'store')->name('posts.store');

    Route::get('/posts/{slug}/edit', 'edit')
        ->where('slug', $slugRegex)
        ->name('posts.edit');

    Route::put('/posts/{slug}', 'update')
        ->where('slug', $slugRegex)
        ->name('posts.update');

    Route::get('/posts/{slug}', 'show')
        ->where('slug', $slugRegex)
        ->name('posts.show');
});

Route::get('/test', function () {
    return view('test');
});