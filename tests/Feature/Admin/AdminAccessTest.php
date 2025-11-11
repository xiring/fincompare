<?php

/**
 * Admin access tests.
 *
 * @covers \Src\Partners\Presentation\Controllers\Admin\PartnerController
 * @covers \Src\Shared\Infrastructure\Http\Middleware\Authenticate
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Src\Auth\Domain\Entities\User;

uses(RefreshDatabase::class);

it('redirects unauthenticated users from admin', function () {
	$this->get(route('admin.partners.index'))->assertRedirect('/login');
});

it('allows admin role to access admin partners index', function () {
	$user = User::factory()->create();
	Role::create(['name' => 'admin']);
	$user->assignRole('admin');

	$this->actingAs($user)->get(route('admin.partners.index'))->assertOk();
});


