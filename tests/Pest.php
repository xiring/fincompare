<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use Src\Auth\Domain\Entities\User;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(TestCase::class)->in('Unit');

Factory::guessFactoryNamesUsing(function (string $modelName) {
    return 'Database\\Factories\\'.class_basename($modelName).'Factory';
});

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Log in as a user with the given role (default: admin).
 */
function actingAsRole(string $roleName = 'admin'): User
{
	$user = User::factory()->create();
	Role::firstOrCreate(['name' => $roleName]);
	$user->assignRole($roleName);
	test()->actingAs($user);
	return $user;
}

/**
 * Log in as admin and return the authenticated user.
 */
function actingAsAdmin(): User
{
	return actingAsRole('admin');
}

/**
 * Assign a role to the provided user and authenticate as them.
 */
function actAs(User $user, string $roleName = 'admin'): User
{
	Role::firstOrCreate(['name' => $roleName]);
	$user->assignRole($roleName);
	test()->actingAs($user);
	return $user;
}
