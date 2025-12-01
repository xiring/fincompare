<?php

use Src\Shared\Presentation\Controllers\Public\ContactController;
use Src\Shared\Presentation\Controllers\Public\FrontendController;
use Illuminate\Support\Facades\Route;
use Src\Auth\Presentation\Controllers\ProfileController;
use Src\Catalog\Presentation\Controllers\Public\ProductController;
use Src\Content\Presentation\Controllers\Public\BlogController;
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

Route::get('/', [FrontendController::class, 'home'])->name('home');

// Static pages
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/privacy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::get('/faq', [FrontendController::class, 'faq'])->name('faq');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Public catalog
Route::get('/products', [ProductController::class, 'index'])->name('products.public.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.public.show');
Route::get('/categories/{category:slug}', [ProductController::class, 'category'])->name('categories.public.show');
Route::post('/compare/toggle', [ProductController::class, 'toggleCompare'])->name('compare.toggle');
Route::get('/compare', [ProductController::class, 'compare'])->name('compare');

// Public lead capture
Route::get('/lead', [LeadController::class, 'create'])->name('leads.create');
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
