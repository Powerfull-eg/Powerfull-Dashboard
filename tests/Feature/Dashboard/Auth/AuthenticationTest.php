<?php

use App\Models\Admin;
use App\Providers\RouteServiceProvider;

test('login screen can be rendered', function () {
    $response = $this->get(route('dashboard.login'));

    $response->assertStatus(200);
});

test('admin can authenticate using the login screen', function () {
    $admin = Admin::factory()->create();

    $response = $this->post(route('dashboard.login'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::DASHBOARD);
});

test('users can not authenticate with invalid password', function () {
    $admin = Admin::factory()->create();

    $this->post(route('dashboard.login'), [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
