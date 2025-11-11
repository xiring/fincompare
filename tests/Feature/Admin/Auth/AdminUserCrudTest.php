<?php

/**
 * Admin User CRUD tests.
 *
 * @covers \Src\Auth\Presentation\Controllers\Admin\AdminUserController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Auth\Domain\Entities\User;

uses(RefreshDatabase::class);

it('stores admin user via json', function () {
    actingAsAdmin();
    $email = 'user+'.fake()->unique()->numberBetween(100, 999).'@example.com';
    $this->postJson(route('admin.users.store'), [
        'name' => fake()->name(),
        'email' => $email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertCreated();
    $this->assertDatabaseHas('users', ['email' => $email]);
});

it('edits admin user via json (acts as show)', function () {
    actingAsAdmin();
    $user = User::factory()->create();
    $this->getJson(route('admin.users.edit', $user))->assertOk()->assertJson(['id' => $user->id]);
});

it('updates admin user via json', function () {
    actingAsAdmin();
    $user = User::factory()->create();
    $newName = $user->name.' Updated';
    $this->putJson(route('admin.users.update', $user), [
        'name' => $newName,
        'email' => $user->email,
    ])->assertOk()->assertJson(['name' => $newName]);
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $newName]);
});

it('deletes admin user via json', function () {
    actingAsAdmin();
    $user = User::factory()->create();
    $this->deleteJson(route('admin.users.destroy', $user))->assertNoContent();
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
