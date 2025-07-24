<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Volt\Volt;
use Livewire\Livewire;

test('se puede mostrar la pantalla de recuperación de contraseña', function () {
    $response = $this->get('/forgot-password');

    $response
        ->assertSeeVolt('pages.auth.forgot-password')
        ->assertOk();
});

test('se puede solicitar el enlace de recuperación de contraseña', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

test('se puede mostrar la pantalla de restablecimiento de contraseña', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response
            ->assertSeeVolt('pages.auth.reset-password')
            ->assertOk();

        return true;
    });
});

test('la contraseña puede ser restablecida con un token válido', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        return Livewire::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'P@ssword1')
            ->set('password_confirmation', 'P@ssword1')
            ->call('resetPassword')
            ->assertHasNoErrors()
            ->assertDispatched('password.reset', [
                'email' => $user->email
            ]);
    });
});