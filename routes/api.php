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
use Src\Shared\Presentation\Controllers\Admin\DashboardStatsController;

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
    Route::get('stats', [DashboardStatsController::class, 'index'])->name('stats.index');
    Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [SiteSettingController::class, 'update'])->name('settings.update');
    Route::resource('partners', PartnerController::class);
    // Define explicit routes with {id} parameter BEFORE resource route to avoid slug-based route model binding
    Route::get('product-categories/{id}', [ProductCategoryController::class, 'show'])->name('product-categories.show');
    Route::get('product-categories/{id}/edit', [ProductCategoryController::class, 'edit'])->name('product-categories.edit');
    Route::patch('product-categories/{id}', [ProductCategoryController::class, 'update'])->name('product-categories.update');
    Route::put('product-categories/{id}', [ProductCategoryController::class, 'update'])->name('product-categories.update');
    Route::delete('product-categories/{id}', [ProductCategoryController::class, 'destroy'])->name('product-categories.destroy');
    // Resource route for index, create, and store (these don't need ID-based lookup)
    Route::get('product-categories', [ProductCategoryController::class, 'index'])->name('product-categories.index');
    Route::get('product-categories/create', [ProductCategoryController::class, 'create'])->name('product-categories.create');
    Route::post('product-categories', [ProductCategoryController::class, 'store'])->name('product-categories.store');
    // Define by-category route BEFORE resource route to avoid route conflict
    // Use {id} instead of {product_category} to avoid slug-based route model binding
    Route::get('attributes/by-category/{id}', [AttributeController::class, 'byCategory'])->name('attributes.by-category');
    // Define explicit routes with {id} parameter BEFORE resource route to avoid route model binding issues
    Route::get('attributes/{id}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
    Route::patch('attributes/{id}', [AttributeController::class, 'update'])->name('attributes.update');
    Route::put('attributes/{id}', [AttributeController::class, 'update'])->name('attributes.update');
    Route::delete('attributes/{id}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
    // Resource route for index and create (these don't need ID-based lookup)
    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::get('attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
    Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');
    Route::get('products/import', [ProductImportController::class, 'create'])->name('products.import');
    Route::post('products/import', [ProductImportController::class, 'store'])->name('products.import.store');
    // Define explicit routes with {id} parameter BEFORE resource route to avoid slug-based route model binding
    Route::get('products/{id}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::patch('products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::put('products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('products/{id}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
    // Resource route for index, create, and store (these don't need ID-based lookup)
    Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('products', [AdminProductController::class, 'store'])->name('products.store');
    Route::post('uploads/wysiwyg', [UploadController::class, 'storeWysiwygImage'])->name('uploads.wysiwyg');
    // Define explicit routes with {id} parameter BEFORE resource route
    Route::get('blogs/{id}', [BlogPostController::class, 'show'])->name('blogs.show');
    Route::get('blogs/{id}/edit', [BlogPostController::class, 'edit'])->name('blogs.edit');
    Route::patch('blogs/{id}', [BlogPostController::class, 'update'])->name('blogs.update');
    Route::put('blogs/{id}', [BlogPostController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{id}', [BlogPostController::class, 'destroy'])->name('blogs.destroy');
    // Resource route for index, create, and store
    Route::get('blogs', [BlogPostController::class, 'index'])->name('blogs.index');
    Route::get('blogs/create', [BlogPostController::class, 'create'])->name('blogs.create');
    Route::post('blogs', [BlogPostController::class, 'store'])->name('blogs.store');
    // Define explicit routes with {id} parameter BEFORE resource route
    Route::get('cms-pages/{id}', [CmsPageController::class, 'show'])->name('cms-pages.show');
    Route::get('cms-pages/{id}/edit', [CmsPageController::class, 'edit'])->name('cms-pages.edit');
    Route::patch('cms-pages/{id}', [CmsPageController::class, 'update'])->name('cms-pages.update');
    Route::put('cms-pages/{id}', [CmsPageController::class, 'update'])->name('cms-pages.update');
    Route::delete('cms-pages/{id}', [CmsPageController::class, 'destroy'])->name('cms-pages.destroy');
    // Resource route for index, create, and store
    Route::get('cms-pages', [CmsPageController::class, 'index'])->name('cms-pages.index');
    Route::get('cms-pages/create', [CmsPageController::class, 'create'])->name('cms-pages.create');
    Route::post('cms-pages', [CmsPageController::class, 'store'])->name('cms-pages.store');
    // Define explicit routes with {id} parameter BEFORE resource route
    Route::get('faqs/{id}', [FaqController::class, 'show'])->name('faqs.show');
    Route::get('faqs/{id}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::patch('faqs/{id}', [FaqController::class, 'update'])->name('faqs.update');
    Route::put('faqs/{id}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('faqs/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    // Resource route for index, create, and store
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::resource('forms', FormController::class);
    Route::post('forms/{form}/duplicate', [FormController::class, 'duplicate'])->name('forms.duplicate');
    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update']);
    Route::get('leads-export', [LeadController::class, 'exportCsv'])->name('leads.export');

    Route::middleware('role:admin')->group(function () {
        Route::get('activity', [ActivityLogController::class, 'index'])->name('activity.index');
        // User Management
        // Define explicit routes with {id} parameter BEFORE resource route
        Route::get('users/{id}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::patch('users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::put('users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        // Resource route for index, create, and store (these don't need ID-based lookup)
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        // Define explicit routes with {id} parameter BEFORE resource route
        Route::get('roles/{id}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::patch('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
        // Resource route for index, create, and store
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        // Define explicit routes with {id} parameter BEFORE resource route
        Route::get('permissions/{id}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::patch('permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::put('permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        // Resource route for index, create, and store
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
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
