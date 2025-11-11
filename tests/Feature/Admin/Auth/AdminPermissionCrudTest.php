<?php

/**
 * Admin Permission CRUD tests.
 *
 * @covers \Src\Auth\Presentation\Controllers\Admin\PermissionController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('stores permission via json', function () {
    actingAsAdmin();
    $name = 'perm-'.fake()->unique()->numberBetween(100, 999);
    $this->postJson(route('admin.permissions.store'), [
        'name' => $name,
    ])->assertCreated();
    $this->assertDatabaseHas('permissions', ['name' => $name]);
});

it('edits permission via json (acts as show)', function () {
    actingAsAdmin();
    $perm = Permission::create(['name' => 'perm-'.fake()->numberBetween(100, 999)]);
    $this->getJson(route('admin.permissions.edit', $perm))->assertOk()->assertJson(['id' => $perm->id]);
});

it('updates permission via json', function () {
    actingAsAdmin();
    $perm = Permission::create(['name' => 'perm-'.fake()->numberBetween(100, 999)]);
    $new = $perm->name.'-updated';
    $this->putJson(route('admin.permissions.update', $perm), [
        'name' => $new,
    ])->assertOk()->assertJson(['name' => $new]);
    $this->assertDatabaseHas('permissions', ['id' => $perm->id, 'name' => $new]);
});

it('deletes permission via json', function () {
    actingAsAdmin();
    $perm = Permission::create(['name' => 'perm-'.fake()->numberBetween(100, 999)]);
    $this->deleteJson(route('admin.permissions.destroy', $perm))->assertNoContent();
    expect(Permission::find($perm->id))->toBeNull();
});
