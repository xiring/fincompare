<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Src\Shared\Presentation\Controllers\Api\ContentController;
use Src\Shared\Presentation\Controllers\Api\SiteSettingsController;
use Src\Catalog\Presentation\Controllers\Public\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes for Vue SPA
Route::prefix('public')->group(function () {
    // Site settings
    Route::get('/site-settings', [SiteSettingsController::class, 'index']);
    Route::get('/api/site-settings', [SiteSettingsController::class, 'index']); // Alias for compatibility

    // Home page data
    Route::get('/home', [ContentController::class, 'home']);

    // FAQs
    Route::get('/faqs', [ContentController::class, 'faqs']);

    // Blog
    Route::get('/blog', [ContentController::class, 'blogIndex']);
    Route::get('/blog/{slug}', [ContentController::class, 'blogShow']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories/{category}', [ProductController::class, 'category']);
    Route::get('/compare', [ProductController::class, 'compare']);
    Route::post('/compare/toggle', [ProductController::class, 'toggleCompare']);
});
