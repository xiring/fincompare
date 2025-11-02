<?php

use Src\Auth\Domain\Entities\User;
use Src\Catalog\Domain\Entities\Product;

it('lists products as JSON for an authenticated user', function () {
    $user = User::factory()->create();
    Product::factory()->count(3)->create();

    $response = $this->actingAs($user)->getJson('/admin/products');

    $response->assertOk();
    $response->assertJsonStructure(['data']);
    $this->assertCount(3, $response->json('data'));
});


