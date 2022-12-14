<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecycleController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [RecycleController::class, 'index'])
        ->name('root');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resource('recycles', RecycleController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');// ポリシーとこれは何が違うのか？

Route::resource('recycles', RecycleController::class) //
    ->only(['show', 'index']);

Route::resource('recycles.comments', CommentController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

require __DIR__ . '/auth.php';
