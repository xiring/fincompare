<?php

use Src\Auth\Presentation\Controllers\ProfileController;
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

Route::get('/', [\Src\Shared\Presentation\Controllers\Public\FrontendController::class, 'home'])->name('home');

// Static pages
Route::get('/about', [\Src\Shared\Presentation\Controllers\Public\FrontendController::class, 'about'])->name('about');
Route::get('/privacy', [\Src\Shared\Presentation\Controllers\Public\FrontendController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\Src\Shared\Presentation\Controllers\Public\FrontendController::class, 'terms'])->name('terms');
Route::view('/contact', 'Shared.Presentation.Views.Public.contact')->name('contact');
Route::post('/contact', [\Src\Shared\Presentation\Controllers\Public\ContactController::class,'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::get('/faq', [\Src\Shared\Presentation\Controllers\Public\FrontendController::class, 'faq'])->name('faq');
Route::get('/blog', [\Src\Content\Presentation\Controllers\Public\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\Src\Content\Presentation\Controllers\Public\BlogController::class, 'show'])->name('blog.show');

// Public catalog
Route::get('/products', [\Src\Catalog\Presentation\Controllers\Public\ProductController::class,'index'])->name('products.public.index');
Route::get('/products/{product}', [\Src\Catalog\Presentation\Controllers\Public\ProductController::class,'show'])->name('products.public.show');
Route::post('/compare/toggle', [\Src\Catalog\Presentation\Controllers\Public\ProductController::class,'toggleCompare'])->name('compare.toggle');
Route::get('/compare', [\Src\Catalog\Presentation\Controllers\Public\ProductController::class,'compare'])->name('compare');

// Public lead capture
Route::get('/lead', [\Src\Leads\Presentation\Controllers\Public\LeadController::class,'create'])->name('leads.create');
Route::post('/leads', [\Src\Leads\Presentation\Controllers\Public\LeadController::class,'store'])->name('leads.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin|editor|viewer'])->name('dashboard');

Route::middleware(['auth', 'role:admin|editor|viewer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
