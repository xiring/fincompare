<?php

/**
 * Admin CMS Page CRUD tests.
 *
 * @covers \Src\Content\Presentation\Controllers\Admin\CmsPageController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Content\Domain\Entities\CmsPage;

uses(RefreshDatabase::class);

it('stores cms page via json', function () {
	actingAsAdmin();
	$payload = [
		'title' => fake()->sentence(3),
		'status' => 'draft',
		'content' => fake()->paragraph(),
	];
	$this->postJson(route('admin.cms-pages.store'), $payload)->assertCreated();
	$this->assertDatabaseHas('cms_pages', ['title' => $payload['title']]);
});

it('shows cms page via json', function () {
	actingAsAdmin();
	$page = CmsPage::factory()->create();
	$this->getJson(route('admin.cms-pages.show', $page))->assertOk()->assertJson(['id' => $page->id]);
});

it('updates cms page via json', function () {
	actingAsAdmin();
	$page = CmsPage::factory()->create(['status' => 'draft']);
	$this->putJson(route('admin.cms-pages.update', $page), [
		'title' => $page->title.' Updated',
		'status' => 'published',
	])->assertOk()->assertJson(['status' => 'published']);
});

it('deletes cms page via json', function () {
	actingAsAdmin();
	$page = CmsPage::factory()->create();
	$this->deleteJson(route('admin.cms-pages.destroy', $page))->assertNoContent();
	$this->assertSoftDeleted('cms_pages', ['id' => $page->id]);
});


