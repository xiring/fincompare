<?php

use Src\Shared\Presentation\Controllers\Public\ContactController;
use Illuminate\Support\Facades\Route;
use Src\Auth\Presentation\Controllers\ProfileController;
use Src\Catalog\Presentation\Controllers\Public\ProductController;
use Src\Leads\Presentation\Controllers\Public\LeadController;

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

// Vue SPA Routes - serve the Vue app for all public pages
Route::get('/', function () {
    return view('public.app');
})->name('home');

Route::get('/about', function () {
    return view('public.app');
})->name('about');

Route::get('/privacy', function () {
    return view('public.app');
})->name('privacy');

Route::get('/terms', function () {
    return view('public.app');
})->name('terms');

Route::get('/contact', function () {
    return view('public.app');
})->name('contact');

// Contact form submission (API endpoint)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/faq', function () {
    return view('public.app');
})->name('faq');

Route::get('/blog', function () {
    return view('public.app');
})->name('blog.index');

Route::get('/blog/{slug}', function () {
    return view('public.app');
})->name('blog.show');

// Public catalog
Route::get('/products', function () {
    return view('public.app');
})->name('products.public.index');

Route::get('/products/{slug}', function () {
    return view('public.app');
})->name('products.public.show');

Route::get('/categories/{slug}', function () {
    return view('public.app');
})->name('categories.public.show');

Route::post('/compare/toggle', [ProductController::class, 'toggleCompare'])->name('compare.toggle');

Route::get('/compare', function () {
    return view('public.app');
})->name('compare');

// Public lead capture
Route::get('/lead', function () {
    return view('public.app');
})->name('leads.create');

Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

Route::get('/dashboard', static function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin|editor|viewer'])->name('dashboard');

Route::middleware(['auth', 'role:admin|editor|viewer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
