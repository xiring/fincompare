<?php

/**
 * Public lead capture tests.
 *
 * @covers \Src\Leads\Presentation\Controllers\Public\LeadController::create
 * @covers \Src\Leads\Presentation\Controllers\Public\LeadController::store
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Catalog\Domain\Entities\Product;

uses(RefreshDatabase::class);

it('shows lead form optionally prefilled with product', function () {
	$product = Product::factory()->create();
	$this->get(route('leads.create', ['product' => $product->id]))
		->assertOk()
		->assertSee($product->name);
});

it('submits lead form and redirects with status', function () {
	$product = Product::factory()->create();
	$response = $this->post(route('leads.store'), [
		'product_id' => $product->id,
		'full_name' => fake()->name(),
		'email' => 'lead+'.fake()->unique()->numberBetween(100,999).'@example.com',
		'phone' => (string)fake()->numerify('##########'),
		'city' => fake()->city(),
		'message' => fake()->sentence(),
	]);
	$response->assertRedirect('/');
	$response->assertSessionHas('status');
});


