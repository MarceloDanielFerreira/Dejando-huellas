<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

test('la página de perfil se muestra correctamente', function () {
    $user = User::factory()->create();
    
    $response = test()->actingAs($user)->get('/profile');
    
    $response->assertOk();
});

test('la información del perfil puede ser actualizada', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('profile.update-profile-information-form')
        ->set('name', 'Usuario de Prueba')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors()
        ->assertDispatched('profile-updated');

    $user->refresh();

    expect($user)
        ->name->toBe('Usuario de Prueba')
        ->email->toBe('test@example.com')
        ->email_verified_at->toBeNull();
});

test('el estado de verificación de email no cambia cuando el email sigue igual', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    Livewire::actingAs($user)
        ->test('profile.update-profile-information-form')
        ->set('name', 'Usuario de Prueba')
        ->set('email', $user->email)
        ->call('updateProfileInformation')
        ->assertHasNoErrors()
        ->assertDispatched('profile-updated');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('el usuario puede eliminar su cuenta', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('profile.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser')
        ->assertHasNoErrors()
        ->assertDispatched('user-deleted');

    expect($user->fresh())->toBeNull();
    expect(Auth::check())->toBeFalse();
});

test('se debe proporcionar la contraseña correcta para eliminar la cuenta', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('profile.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors('password');

    expect($user->fresh())->not->toBeNull();
});