<?php

/**
 * Public catalog tests.
 *
 * @covers \Src\Catalog\Presentation\Controllers\Public\ProductController::index
 * @covers \Src\Catalog\Presentation\Controllers\Public\ProductController::show
 * @covers \Src\Catalog\Presentation\Controllers\Public\ProductController::compare
 * @covers \Src\Catalog\Presentation\Controllers\Public\ProductController::toggleCompare
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

uses(RefreshDatabase::class);

it('lists products with filters and json chunk', function () {
	Product::factory()->count(3)->create();
	$category = ProductCategory::factory()->create();
	$partner = Partner::factory()->create();
	$name = ucfirst(fake()->unique()->words(2, true));
	Product::factory()->create([
		'product_category_id' => $category->id,
		'partner_id' => $partner->id,
		'name' => $name
	]);

	$this->get(route('products.public.index'))
		->assertOk()
		->assertSee('All Products');

	$this->get(route('products.public.index', ['q' => explode(' ', $name)[0]]))
		->assertOk()
		->assertSee($name);

	$this->get(route('products.public.index', ['category_id' => $category->id]))
		->assertOk();

	$this->getJson(route('products.public.index', ['q' => explode(' ', $name)[0]]))
		->assertOk()
		->assertJsonStructure(['html', 'next']);
});

it('shows product details', function () {
	$product = Product::factory()->create();
	$this->get(route('products.public.show', $product))
		->assertOk()
		->assertSee($product->name);
});

it('toggles compare list via ajax and shows compare page', function () {
	$p1 = Product::factory()->create();
	$p2 = Product::factory()->create();

	$this->postJson(route('compare.toggle'), ['id' => $p1->id, 'selected' => true])
		->assertOk()
		->assertJson(['ok' => true]);

	$this->postJson(route('compare.toggle'), ['id' => $p2->id, 'selected' => true])
		->assertOk();

	$this->get(route('compare', ['products' => implode(',', [$p1->id, $p2->id])]))
		->assertOk();
});


