<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/hello-world/{name}', [HelloController::class, 'index']);

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::post('/', [PostController::class, 'store'])->name('posts.store');
Route::get('/create', [PostController::class, 'create'])->name('posts.create');
Route::get('/{slug}', [PostController::class, 'show'])->name('posts.show');
