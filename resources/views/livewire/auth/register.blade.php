<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $roles = [];
    public string $telefono = '';
    public string $direccion = '';
    public string $ci = '';
    public $foto_perfil;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['in:hogar_temporal,adoptante'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ci' => ['nullable', 'string', 'max:30'],
            'foto_perfil' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($this->foto_perfil) {
            $validated['foto_perfil'] = $this->foto_perfil->store('perfiles', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));
        $user->assignRole($validated['roles']);

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <!-- Roles -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Selecciona tu rol') }}</label>
            <div class="flex flex-col gap-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="roles" value="hogar_temporal" class="form-checkbox">
                    <span class="ml-2">Hogar temporal</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="roles" value="adoptante" class="form-checkbox">
                    <span class="ml-2">Adoptante</span>
                </label>
            </div>
            @error('roles')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Telefono -->
        <flux:input
            wire:model="telefono"
            :label="__('Teléfono')"
            type="text"
            autocomplete="tel"
            :placeholder="__('Teléfono')"
        />
        <!-- Dirección -->
        <flux:input
            wire:model="direccion"
            :label="__('Dirección')"
            type="text"
            autocomplete="street-address"
            :placeholder="__('Dirección')"
        />
        <!-- CI -->
        <flux:input
            wire:model="ci"
            :label="__('CI')"
            type="text"
            autocomplete="off"
            :placeholder="__('CI')"
        />
        <!-- Foto de perfil -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Foto de perfil') }}</label>
            <input type="file" wire:model="foto_perfil" accept="image/*" class="block w-full text-sm text-gray-500" />
            @error('foto_perfil')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear cuenta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600">
        <span>{{ __('¿Ya tienes una cuenta?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar Sesión') }}</flux:link>
    </div>
</div>
