<?php

/**
 * Admin Product CRUD tests.
 *
 * @covers \Src\Catalog\Presentation\Controllers\Admin\ProductController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

uses(RefreshDatabase::class);

it('stores product via json', function () {
	actingAsAdmin();
	$partner = Partner::factory()->create();
	$category = ProductCategory::factory()->create();
	$payload = [
		'partner_id' => $partner->id,
		'product_category_id' => $category->id,
		'name' => 'Pro Widget',
		'status' => 'active',
	];
	$response = $this->postJson(route('admin.products.store'), $payload);
	$response->assertCreated();
	$this->assertDatabaseHas('products', ['name' => 'Pro Widget', 'partner_id' => $partner->id]);
});

it('shows product via json', function () {
	actingAsAdmin();
	$product = Product::factory()->create();
	$this->getJson(route('admin.products.show', $product))
		->assertOk()
		->assertJson(['id' => $product->id, 'name' => $product->name]);
});

it('updates product via json', function () {
	actingAsAdmin();
	$product = Product::factory()->create(['status' => 'inactive']);
	$this->putJson(route('admin.products.update', $product), [
		'partner_id' => $product->partner_id,
		'product_category_id' => $product->product_category_id,
		'name' => 'Ultra Widget',
		'status' => 'active',
	])->assertOk()->assertJson(['name' => 'Ultra Widget', 'status' => 'active']);
	$this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Ultra Widget']);
});

it('deletes product via json', function () {
	actingAsAdmin();
	$product = Product::factory()->create();
	$this->deleteJson(route('admin.products.destroy', $product))->assertNoContent();
	$this->assertSoftDeleted('products', ['id' => $product->id]);
});


