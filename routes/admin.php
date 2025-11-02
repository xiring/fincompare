<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['web','auth'])->prefix('admin')->name('admin.')->group(function(){
    Route::resource('partners', \Src\Partners\Presentation\Controllers\Admin\PartnerController::class);
    Route::resource('product-categories', \Src\Catalog\Presentation\Controllers\Admin\ProductCategoryController::class);
    Route::resource('attributes', \Src\Catalog\Presentation\Controllers\Admin\AttributeController::class)->except(['show']);
    Route::get('attributes/by-category/{product_category}', [\Src\Catalog\Presentation\Controllers\Admin\AttributeController::class,'byCategory'])->name('attributes.by-category');
    Route::get('products/import', [\Src\Catalog\Presentation\Controllers\Admin\ProductImportController::class,'create'])->name('products.import');
    Route::post('products/import', [\Src\Catalog\Presentation\Controllers\Admin\ProductImportController::class,'store'])->name('products.import.store');
    Route::resource('products', \Src\Catalog\Presentation\Controllers\Admin\ProductController::class);
    Route::post('uploads/wysiwyg', [\Src\Content\Presentation\Controllers\UploadController::class,'storeWysiwygImage'])->name('uploads.wysiwyg');
    Route::resource('blogs', \Src\Content\Presentation\Controllers\Admin\BlogPostController::class);
    Route::resource('cms-pages', \Src\Content\Presentation\Controllers\Admin\CmsPageController::class);
    Route::resource('leads', \Src\Leads\Presentation\Controllers\Admin\LeadController::class)->only(['index','show','update']);
    Route::get('leads-export', [\Src\Leads\Presentation\Controllers\Admin\LeadController::class,'exportCsv'])->name('leads.export');

    Route::middleware('role:admin')->group(function(){
        Route::get('activity', [\Src\Shared\Presentation\Controllers\ActivityLogController::class,'index'])->name('activity.index');
        // User Management
        Route::resource('users', \Src\Auth\Presentation\Controllers\Admin\AdminUserController::class);
        Route::resource('roles', \Src\Auth\Presentation\Controllers\Admin\RoleController::class);
        Route::resource('permissions', \Src\Auth\Presentation\Controllers\Admin\PermissionController::class)->except(['show']);
    });
});
