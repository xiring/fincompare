<?php

/**
 * Admin Role CRUD tests.
 *
 * @covers \Src\Auth\Presentation\Controllers\Admin\RoleController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('stores role via json', function () {
	actingAsAdmin();
	$name = 'role-'.fake()->unique()->numberBetween(100,999);
	$this->postJson(route('admin.roles.store'), [
		'name' => $name,
	])->assertCreated();
	$this->assertDatabaseHas('roles', ['name' => $name]);
});

it('edits role via json (acts as show)', function () {
	actingAsAdmin();
	$role = Role::create(['name' => 'editor-'.fake()->numberBetween(100,999)]);
	$this->getJson(route('admin.roles.edit', $role))->assertOk()->assertJson(['id' => $role->id]);
});

it('updates role via json', function () {
	actingAsAdmin();
	$role = Role::create(['name' => 'viewer-'.fake()->numberBetween(100,999)]);
	$new = $role->name.'-updated';
	$this->putJson(route('admin.roles.update', $role), [
		'name' => $new,
	])->assertOk()->assertJson(['name' => $new]);
	$this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => $new]);
});

it('deletes role via json', function () {
	actingAsAdmin();
	$role = Role::create(['name' => 'temp-'.fake()->numberBetween(100,999)]);
	$this->deleteJson(route('admin.roles.destroy', $role))->assertNoContent();
	expect(Role::find($role->id))->toBeNull();
});


