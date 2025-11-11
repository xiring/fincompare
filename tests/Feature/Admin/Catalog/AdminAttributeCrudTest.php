<?php

/**
 * Admin Attribute CRUD tests.
 *
 * @covers \Src\Catalog\Presentation\Controllers\Admin\AttributeController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\ProductCategory;

uses(RefreshDatabase::class);

it('stores attribute via json', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create();
    $payload = [
        'product_category_id' => $cat->id,
        'name' => 'Length',
        'slug' => null,
        'data_type' => 'number',
        'unit' => 'cm',
        'is_filterable' => true,
        'is_required' => false,
        'sort_order' => 1,
    ];
    $response = $this->postJson(route('admin.attributes.store'), $payload);
    $response->assertCreated();
    $this->assertDatabaseHas('attributes', ['product_category_id' => $cat->id, 'name' => 'Length']);
});

it('edits attribute via json (acts as show)', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create();
    $attr = Attribute::create([
        'product_category_id' => $cat->id,
        'name' => 'Width',
        'data_type' => 'number',
        'slug' => 'width',
    ]);
    $this->getJson(route('admin.attributes.edit', $attr))
        ->assertOk()
        ->assertJson(['id' => $attr->id, 'name' => 'Width']);
});

it('updates attribute via json', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create();
    $attr = Attribute::create([
        'product_category_id' => $cat->id,
        'name' => 'Height',
        'data_type' => 'number',
        'slug' => 'height',
    ]);
    $this->putJson(route('admin.attributes.update', $attr), [
        'product_category_id' => $cat->id,
        'name' => 'Height Updated',
        'data_type' => 'number',
    ])->assertOk()->assertJson(['name' => 'Height Updated']);
});

it('deletes attribute via json', function () {
    actingAsAdmin();
    $cat = ProductCategory::factory()->create();
    $attr = Attribute::create([
        'product_category_id' => $cat->id,
        'name' => 'Temp',
        'data_type' => 'number',
        'slug' => 'temp',
    ]);
    $this->deleteJson(route('admin.attributes.destroy', $attr))->assertNoContent();
    $this->assertSoftDeleted('attributes', ['id' => $attr->id]);
});
