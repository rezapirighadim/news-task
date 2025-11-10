<?php

use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can get user preferences', function () {
    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/preferences');

    $response->assertStatus(200);
});

test('can update preferences', function () {
    Sanctum::actingAs($this->user);

    $source = Source::factory()->create();
    $category = Category::factory()->create();

    $response = $this->postJson('/api/v1/preferences', [
        'preferred_sources' => [$source->id],
        'preferred_categories' => [$category->id],
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Preferences updated successfully']);

    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $this->user->id,
    ]);
});

test('validates preference data', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/v1/preferences', [
        'preferred_sources' => [99999], // non-existent
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('preferred_sources.0');
});

test('requires authentication for preferences', function () {

    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->getJson('/api/v1/preferences');

    $response->assertStatus(401);
});
