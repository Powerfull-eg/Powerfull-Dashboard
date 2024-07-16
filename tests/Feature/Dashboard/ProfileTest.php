<?php

use App\Models\Admin;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('profile page is displayed', function () {
    $user = Admin::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard.profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = Admin::factory()->create();

    $response = $this->actingAs($user)->put(route('dashboard.profile.update'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('dashboard.profile.edit'));

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
});
