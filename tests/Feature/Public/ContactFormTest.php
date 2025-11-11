<?php

/**
 * Contact form tests.
 *
 * @covers \App\Shared\Presentation\Controllers\Public\ContactController::store
 */

use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Src\Shared\Application\Mail\ContactMessageReceived;

uses(RefreshDatabase::class);

dataset('valid_email_domains', ['gmail.com', 'yahoo.com', 'outlook.com']);

it('validates and submits contact form', function (string $domain) {
    config()->set('contact.min_submit_seconds', 0);
    config()->set('mail.from.address', 'admin@example.com');
    Mail::fake();
    $this->withoutMiddleware(ConvertEmptyStringsToNull::class);

    $response = $this->post(route('contact.store'), [
        'name' => fake()->name(),
        'email' => 'user@'.$domain,
        'message' => fake()->sentence(8),
        'hp' => '',
        'submitted_at' => now()->timestamp,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'message-sent');
    Mail::assertQueued(ContactMessageReceived::class);
})->with('valid_email_domains');

it('rejects fast submissions via submitted_at', function () {
    config()->set('contact.min_submit_seconds', 3);
    $response = $this->from(route('contact'))->post(route('contact.store'), [
        'name' => 'Bot',
        'email' => 'bot@example.com',
        'message' => 'Spam',
        'hp' => '',
        'submitted_at' => now()->timestamp, // too soon
    ]);
    $response->assertRedirect();
    $response->assertSessionHasErrors('submitted_at');
});
