<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Src\Auth\Presentation\Controllers\Admin\AdminUserController;
use Src\Auth\Presentation\Controllers\Admin\PermissionController;
use Src\Auth\Presentation\Controllers\Admin\RoleController;
use Src\Catalog\Presentation\Controllers\Admin\AttributeController;
use Src\Catalog\Presentation\Controllers\Admin\ProductCategoryController;
use Src\Catalog\Presentation\Controllers\Admin\ProductController as AdminProductController;
use Src\Catalog\Presentation\Controllers\Admin\ProductImportController;
use Src\Catalog\Presentation\Controllers\Public\ProductController;
use Src\Content\Presentation\Controllers\Admin\BlogPostController;
use Src\Content\Presentation\Controllers\Admin\CmsPageController;
use Src\Content\Presentation\Controllers\Admin\FaqController;
use Src\Content\Presentation\Controllers\UploadController;
use Src\Forms\Presentation\Controllers\Admin\FormController;
use Src\Leads\Presentation\Controllers\Admin\LeadController;
use Src\Partners\Presentation\Controllers\Admin\PartnerController;
use Src\Settings\Presentation\Controllers\Admin\SiteSettingController;
use Src\Auth\Presentation\Controllers\ProfileController;
use Src\Shared\Presentation\Controllers\ActivityLogController;
use Src\Shared\Presentation\Controllers\Api\ContentController;
use Src\Shared\Presentation\Controllers\Api\SiteSettingsController;

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

// Profile API routes
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// Admin API routes - all admin endpoints under /api/admin
Route::middleware(['auth', 'throttle:120,1', 'role:admin|editor|viewer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [SiteSettingController::class, 'update'])->name('settings.update');
    Route::resource('partners', PartnerController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('attributes', AttributeController::class)->except(['show']);
    Route::get('attributes/by-category/{product_category}', [AttributeController::class, 'byCategory'])->name('attributes.by-category');
    Route::get('products/import', [ProductImportController::class, 'create'])->name('products.import');
    Route::post('products/import', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
    Route::post('uploads/wysiwyg', [UploadController::class, 'storeWysiwygImage'])->name('uploads.wysiwyg');
    Route::resource('blogs', BlogPostController::class);
    Route::resource('cms-pages', CmsPageController::class);
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('forms', FormController::class);
    Route::post('forms/{form}/duplicate', [FormController::class, 'duplicate'])->name('forms.duplicate');
    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update']);
    Route::get('leads-export', [LeadController::class, 'exportCsv'])->name('leads.export');

    Route::middleware('role:admin')->group(function () {
        Route::get('activity', [ActivityLogController::class, 'index'])->name('activity.index');
        // User Management
        Route::resource('users', AdminUserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class)->except(['show']);
    });
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
