<?php

/**
 * Admin Product Category CRUD tests.
 *
 * @covers \Src\Catalog\Presentation\Controllers\Admin\ProductCategoryController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Catalog\Domain\Entities\ProductCategory;

uses(RefreshDatabase::class);

it('stores product category via json', function () {
    actingAsAdmin();
    $payload = [
        'name' => fake()->unique()->words(2, true),
        'description' => fake()->sentence(),
        'is_active' => true,
    ];
    $response = $this->postJson(route('admin.product-categories.store'), $payload);
    $response->assertCreated();
    $this->assertDatabaseHas('product_categories', ['name' => $payload['name']]);
});

it('shows product category via json', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create();
    $this->getJson(route('admin.product-categories.show', $cat))
        ->assertOk()
        ->assertJson(['id' => $cat->id, 'name' => $cat->name]);
});

it('updates product category via json', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create(['is_active' => false]);
    $newName = ucfirst(fake()->unique()->words(2, true));
    $this->putJson(route('admin.product-categories.update', $cat), [
        'name' => $newName,
        'description' => fake()->sentence(),
        'is_active' => true,
    ])->assertOk()->assertJson(['name' => $newName]);
    $this->assertDatabaseHas('product_categories', ['id' => $cat->id, 'name' => $newName, 'is_active' => 1]);
});

it('deletes product category via json', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create();
    $this->deleteJson(route('admin.product-categories.destroy', $cat))->assertNoContent();
    $this->assertSoftDeleted('product_categories', ['id' => $cat->id]);
});
