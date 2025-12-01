<?php

use Illuminate\Support\Facades\Route;
use Src\Auth\Presentation\Controllers\Admin\AdminUserController;
use Src\Auth\Presentation\Controllers\Admin\PermissionController;
use Src\Auth\Presentation\Controllers\Admin\RoleController;
use Src\Catalog\Presentation\Controllers\Admin\AttributeController;
use Src\Catalog\Presentation\Controllers\Admin\ProductCategoryController;
use Src\Catalog\Presentation\Controllers\Admin\ProductController;
use Src\Catalog\Presentation\Controllers\Admin\ProductImportController;
use Src\Content\Presentation\Controllers\Admin\BlogPostController;
use Src\Content\Presentation\Controllers\Admin\CmsPageController;
use Src\Content\Presentation\Controllers\Admin\FaqController;
use Src\Content\Presentation\Controllers\UploadController;
use Src\Leads\Presentation\Controllers\Admin\LeadController;
use Src\Partners\Presentation\Controllers\Admin\PartnerController;
use Src\Settings\Presentation\Controllers\Admin\SiteSettingController;
use Src\Shared\Presentation\Controllers\ActivityLogController;
use Src\Forms\Presentation\Controllers\Admin\FormController;

Route::middleware(['web', 'auth', 'throttle:120,1', 'role:admin|editor|viewer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [SiteSettingController::class, 'update'])->name('settings.update');
    Route::resource('partners', PartnerController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('attributes', AttributeController::class)->except(['show']);
    Route::get('attributes/by-category/{product_category}', [AttributeController::class, 'byCategory'])->name('attributes.by-category');
    Route::get('products/import', [ProductImportController::class, 'create'])->name('products.import');
    Route::post('products/import', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::resource('products', ProductController::class);
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
