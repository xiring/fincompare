<?php

/**
 * Site settings update tests.
 *
 * @covers \Src\Settings\Presentation\Controllers\Admin\SiteSettingController::update
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Src\Settings\Domain\Entities\SiteSetting;

uses(RefreshDatabase::class);

it('updates site settings and stores logo/favicon', function () {
	actingAsAdmin();
	Storage::fake('public');

	$payload = [
		'site_name' => fake()->company(),
		'site_slogon' => fake()->catchPhrase(),
		'logo' => UploadedFile::fake()->image('logo.png', 120, 40),
		'favicon' => UploadedFile::fake()->image('favicon.png', 32, 32),
	];

	$response = $this->patch(route('admin.settings.update'), $payload);
	$response->assertRedirect();
	$response->assertSessionHas('status', 'settings-updated');

	$settings = SiteSetting::query()->firstOrFail();
	expect($settings->site_name)->toBe($payload['site_name']);
	expect($settings->logo)->not()->toBeNull();
	expect($settings->favicon)->not()->toBeNull();

	expect(Storage::disk('public')->exists($settings->logo))->toBeTrue();
	expect(Storage::disk('public')->exists($settings->favicon))->toBeTrue();
});


