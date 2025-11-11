<?php

/**
 * Admin FAQ CRUD tests.
 *
 * @covers \Src\Content\Presentation\Controllers\Admin\FaqController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Content\Domain\Entities\Faq;

uses(RefreshDatabase::class);

it('stores faq via json', function () {
    actingAsAdmin();
    $payload = [
        'question' => fake()->sentence(6),
        'answer' => fake()->sentence(12),
    ];
    $this->postJson(route('admin.faqs.store'), $payload)->assertCreated();
    $this->assertDatabaseHas('faqs', ['question' => $payload['question']]);
});

it('edits faq via json (acts as show)', function () {
    actingAsAdmin();
    $faq = Faq::create(['question' => 'Q1', 'answer' => 'A1']);
    $this->getJson(route('admin.faqs.edit', $faq))->assertOk()->assertJson(['id' => $faq->id]);
});

it('updates faq via json', function () {
    actingAsAdmin();
    $faq = Faq::create(['question' => 'Old', 'answer' => 'A']);
    $this->putJson(route('admin.faqs.update', $faq), [
        'question' => 'New',
        'answer' => 'B',
    ])->assertOk()->assertJson(['question' => 'New']);
});

it('deletes faq via json', function () {
    actingAsAdmin();
    $faq = Faq::create(['question' => 'Temp', 'answer' => 'A']);
    $this->deleteJson(route('admin.faqs.destroy', $faq))->assertNoContent();
    $this->assertSoftDeleted('faqs', ['id' => $faq->id]);
});
