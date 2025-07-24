<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('la contraseña puede ser actualizada', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    Livewire::actingAs($user)
        ->test('profile.update-password-form')
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertDispatched('password-updated');

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('se debe proporcionar la contraseña correcta para actualizar', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    Livewire::actingAs($user)
        ->test('profile.update-password-form')
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);
});