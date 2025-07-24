<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;


new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $ci = '';
    public $foto_perfil;
    public string $foto_perfil_url = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->telefono = Auth::user()->telefono ?? '';
        $this->direccion = Auth::user()->direccion ?? '';
        $this->ci = Auth::user()->ci ?? '';
        $this->foto_perfil_url = Auth::user()->foto_perfil ? asset('storage/' . Auth::user()->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name);
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ci' => ['nullable', 'string', 'max:30'],
            'foto_perfil' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($this->foto_perfil) {
            $validated['foto_perfil'] = $this->foto_perfil->store('perfiles', 'public');
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->foto_perfil_url = $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name);

        $this->dispatch('profile-updated', name: $user->name);
        $this->dispatch('toast', message: __('Perfil actualizado correctamente.'), type: 'success');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualiza la información de tu perfil y dirección de correo electrónico.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Tu dirección de correo electrónico no está verificada.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <div class="flex flex-col items-center">
                <img src="{{ $foto_perfil_url }}" alt="Foto de perfil" class="h-20 w-20 rounded-full object-cover mb-2" />
                <input type="file" wire:model="foto_perfil" accept="image/*" class="block w-full text-sm text-gray-500" />
                @error('foto_perfil')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div>
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input wire:model="telefono" id="telefono" name="telefono" type="text" class="mt-1 block w-full" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
        </div>
        <div>
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input wire:model="direccion" id="direccion" name="direccion" type="text" class="mt-1 block w-full" autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
        </div>
        <div>
            <x-input-label for="ci" :value="__('CI')" />
            <x-text-input wire:model="ci" id="ci" name="ci" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('ci')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Guardado.') }}
            </x-action-message>
        </div>
    </form>
</section>
