<?php

/**
 * Public pages tests.
 *
 * @covers \App\Shared\Presentation\Controllers\Public\FrontendController::home
 * @covers \App\Shared\Presentation\Controllers\Public\FrontendController::about
 * @covers \App\Shared\Presentation\Controllers\Public\FrontendController::privacy
 * @covers \App\Shared\Presentation\Controllers\Public\FrontendController::terms
 * @covers \Src\Content\Presentation\Controllers\Public\BlogController::index
 * @covers \Src\Content\Presentation\Controllers\Public\BlogController::show
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Content\Domain\Entities\BlogPost;

uses(RefreshDatabase::class);

it('shows home page', function () {
    $this->get(route('home'))->assertOk();
});

it('shows static pages', function () {
    $this->get(route('about'))->assertOk();
    $this->get(route('privacy'))->assertOk();
    $this->get(route('terms'))->assertOk();
});

it('shows blog index and blog post page', function () {
    $published = BlogPost::factory()->create(['status' => 'published']);
    $this->get(route('blog.index'))->assertOk();
    $this->get(route('blog.show', $published->slug))->assertOk();
});
