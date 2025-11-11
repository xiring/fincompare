<?php

/**
 * Admin Product import tests.
 *
 * @covers \Src\Catalog\Presentation\Controllers\Admin\ProductImportController::store
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('queues product import with csv file', function () {
    actingAsAdmin();

    Storage::fake('local');
    $file = UploadedFile::fake()->create('products.csv', 1, 'text/csv');

    $this->post(route('admin.products.import.store'), [
        'file' => $file,
        'delimiter' => ',',
        'has_header' => true,
    ])->assertRedirect(route('admin.products.index'));
});
