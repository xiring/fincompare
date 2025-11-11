<?php

/**
 * Profile management tests.
 *
 * @covers \Src\Auth\Presentation\Controllers\ProfileController::edit
 * @covers \Src\Auth\Presentation\Controllers\ProfileController::update
 * @covers \Src\Auth\Presentation\Controllers\ProfileController::updatePassword
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Auth\Domain\Entities\User;

uses(RefreshDatabase::class);

it('shows profile edit page', function () {
    $user = actingAsRole('viewer');
    $this->get(route('profile.edit'))->assertOk();
});

it('updates profile details', function () {
    $user = actingAsRole('viewer');
    $this->patch(route('profile.update'), [
        'name' => $user->name.' Updated',
        'email' => 'profile+'.fake()->unique()->numberBetween(100, 999).'@example.com',
    ])->assertRedirect();
});

it('updates password with correct current password', function () {
    $user = User::factory()->create(['password' => bcrypt('password123')]);
    actAs($user, 'viewer');
    $this->put(route('password.update'), [
        'current_password' => 'password123',
        'password' => 'newsecret123',
        'password_confirmation' => 'newsecret123',
    ])->assertRedirect();
});
