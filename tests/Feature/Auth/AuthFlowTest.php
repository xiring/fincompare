<?php

/**
 * Authentication flow tests.
 *
 * @covers \Src\Auth\Presentation\Controllers\LoginController::create
 * @covers \Src\Auth\Presentation\Controllers\LoginController::store
 * @covers \Src\Auth\Presentation\Controllers\LoginController::destroy
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Auth\Domain\Entities\User;

uses(RefreshDatabase::class);

it('shows login page to guests and redirects authenticated users', function () {
	$this->get(route('login'))->assertOk();

	$user = User::factory()->create();
	$this->actingAs($user)->get(route('login'))->assertRedirect('/dashboard');
});

dataset('remember_flag', [true, false]);

it('logs in with valid credentials and logs out', function (bool $remember) {
	$email = 'user+'.fake()->unique()->numberBetween(100,999).'@example.com';
	$user = User::factory()->create(['email' => $email, 'password' => bcrypt('password')]);

	$this->post('/login', [
		'email' => $email,
		'password' => 'password',
		'remember' => $remember,
	])->assertRedirect('/dashboard');

	$this->post(route('logout'))->assertRedirect('/');
})->with('remember_flag');


