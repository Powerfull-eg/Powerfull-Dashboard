<?php

use App\Models\Admin;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('dashboard.password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $admin = Admin::factory()->create();

    $this->post(route('dashboard.password.email'), [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo($admin, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $admin = Admin::factory()->create();

    $this->post(route('dashboard.password.email'), [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo($admin, ResetPassword::class, function ($notification) {
        $response = $this->get(route('dashboard.password.reset', [
            'token' => $notification->token,
        ]));

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $admin = Admin::factory()->create();

    $this->post(route('dashboard.password.email'), [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo($admin, ResetPassword::class, function ($notification) use ($admin) {
        $response = $this->post(route('dashboard.password.store'), [
            'token' => $notification->token,
            'email' => $admin->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();

        return true;
    });
});
