<?php

/**
 * Core admin route reachability tests and basic actions.
 *
 * @covers \Src\Settings\Presentation\Controllers\Admin\SiteSettingController
 * @covers \Src\Partners\Presentation\Controllers\Admin\PartnerController
 * @covers \Src\Catalog\Presentation\Controllers\Admin\ProductCategoryController
 * @covers \Src\Catalog\Presentation\Controllers\Admin\AttributeController
 * @covers \Src\Catalog\Presentation\Controllers\Admin\ProductController
 * @covers \Src\Content\Presentation\Controllers\UploadController
 * @covers \Src\Leads\Presentation\Controllers\Admin\LeadController
 * @covers \Src\Shared\Presentation\Controllers\ActivityLogController
 * @covers \Src\Auth\Presentation\Controllers\Admin\AdminUserController
 * @covers \Src\Auth\Presentation\Controllers\Admin\RoleController
 * @covers \Src\Auth\Presentation\Controllers\Admin\PermissionController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Src\Auth\Domain\Entities\User;

uses(RefreshDatabase::class);

dataset('admin_pages', [
	'admin.settings.edit',
	'admin.partners.index',
	'admin.product-categories.index',
	'admin.attributes.index',
	'admin.products.index',
	'admin.products.import',
	'admin.blogs.index',
	'admin.cms-pages.index',
	'admin.faqs.index',
	'admin.leads.index',
	'admin.activity.index',
]);

it('admin can access core admin pages', function (string $routeName) {
	actingAsAdmin();
	$this->get(route($routeName))->assertOk();
})->with('admin_pages');

it('admin can update site settings', function () {
	actingAsAdmin();
	$this->patch(route('admin.settings.update'), [
		'site_name' => fake()->company(),
	])->assertRedirect();
	session()->get('status'); // just touch session to avoid unused var warning
});

dataset('image_files', [
	fn() => UploadedFile::fake()->image('img.png', 64, 64),
	fn() => UploadedFile::fake()->image('img.jpg', 128, 128),
	fn() => UploadedFile::fake()->image('img.webp', 48, 48),
]);

it('wysiwyg upload accepts images and returns url', function (UploadedFile $file) {
	actingAsAdmin();
	Storage::fake('public');
	$this->post(route('admin.uploads.wysiwyg'), [
		'image' => $file,
	])->assertOk()->assertJsonStructure(['url']);
})->with('image_files');

it('leads export streams csv', function () {
	actingAsAdmin();
	$response = $this->get(route('admin.leads.export'));
	$response->assertOk();
	$this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
});

it('user management pages accessible to admin', function () {
	actingAsAdmin();
	$this->get(route('admin.users.index'))->assertOk();
	$this->get(route('admin.roles.index'))->assertOk();
	$this->get(route('admin.permissions.index'))->assertOk();
});


