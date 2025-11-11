<?php

/**
 * Admin Blog CRUD tests.
 *
 * @covers \Src\Content\Presentation\Controllers\Admin\BlogPostController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Content\Domain\Entities\BlogPost;

uses(RefreshDatabase::class);

it('stores blog via json', function () {
    actingAsAdmin();
    $payload = [
        'title' => fake()->sentence(3),
        'status' => 'draft',
        'content' => fake()->paragraph(),
    ];
    $response = $this->postJson(route('admin.blogs.store'), $payload);
    $response->assertCreated();
    $this->assertDatabaseHas('blog_posts', ['title' => $payload['title']]);
});

it('shows blog via json', function () {
    actingAsAdmin();
    $post = BlogPost::factory()->create();
    $this->getJson(route('admin.blogs.show', $post))->assertOk()->assertJson(['id' => $post->id]);
});

it('updates blog via json', function () {
    actingAsAdmin();
    $post = BlogPost::factory()->create(['status' => 'draft']);
    $this->putJson(route('admin.blogs.update', $post), [
        'title' => $post->title.' Updated',
        'status' => 'published',
    ])->assertOk()->assertJson(['status' => 'published']);
});

it('deletes blog via json', function () {
    actingAsAdmin();
    $post = BlogPost::factory()->create();
    $this->deleteJson(route('admin.blogs.destroy', $post))->assertNoContent();
    $this->assertSoftDeleted('blog_posts', ['id' => $post->id]);
});
