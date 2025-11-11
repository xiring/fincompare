<?php

/**
 * Admin Partner CRUD tests.
 *
 * @covers \Src\Partners\Presentation\Controllers\Admin\PartnerController
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Partners\Domain\Entities\Partner;

uses(RefreshDatabase::class);

it('stores a partner via json', function () {
    actingAsAdmin();
    $payload = [
        'name' => fake()->company(),
        'website_url' => 'https://'.fake()->domainName(),
        'contact_email' => 'contact+'.fake()->numberBetween(100, 999).'@example.com',
        'contact_phone' => (string) fake()->numerify('##########'),
        'status' => 'active',
    ];
    $response = $this->postJson(route('admin.partners.store'), $payload);
    $response->assertCreated();
    $data = $response->json();
    expect($data)->toHaveKey('id');
    expect($data['name'])->toBe($payload['name']);
    $this->assertDatabaseHas('partners', ['name' => $payload['name']]);
});

it('shows a partner via json', function () {
    actingAsAdmin();
    $partner = Partner::factory()->create();
    $response = $this->getJson(route('admin.partners.show', $partner));
    $response->assertOk()->assertJson(['id' => $partner->id, 'name' => $partner->name]);
});

it('updates a partner via json', function () {
    actingAsAdmin();
    $partner = Partner::factory()->create(['status' => 'inactive']);
    $newName = fake()->company().' Ltd';
    $response = $this->putJson(route('admin.partners.update', $partner), [
        'name' => $newName,
        'status' => 'active',
    ]);
    $response->assertOk()->assertJson(['name' => $newName, 'status' => 'active']);
    $this->assertDatabaseHas('partners', ['id' => $partner->id, 'name' => $newName, 'status' => 'active']);
});

it('soft deletes a partner via json', function () {
    actingAsAdmin();
    $partner = Partner::factory()->create();
    $response = $this->deleteJson(route('admin.partners.destroy', $partner));
    $response->assertNoContent();
    $this->assertSoftDeleted('partners', ['id' => $partner->id]);
});
