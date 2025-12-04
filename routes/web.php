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

// API/Form submission routes
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

Route::post('/compare/toggle', [ProductController::class, 'toggleCompare'])->name('compare.toggle');

// Authenticated routes (must be before catch-all)
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

// Vue SPA catch-all route - must be last to catch all remaining public GET requests
// Vue Router handles client-side routing for all these paths
Route::get('/{path?}', function () {
    return view('public.app');
})->where('path', '^(?!api|admin|dashboard|profile|horizon|telescope).*')
  ->name('spa');
