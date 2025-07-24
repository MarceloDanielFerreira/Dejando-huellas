<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div x-data="{ open: true }" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <button @click="open = !open" class="w-full flex justify-between items-center text-lg font-semibold text-gray-800 focus:outline-none">
                    {{ __('Información de perfil') }}
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" x-transition class="max-w-xl mt-4">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div x-data="{ open: false }" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <button @click="open = !open" class="w-full flex justify-between items-center text-lg font-semibold text-gray-800 focus:outline-none">
                    {{ __('Actualizar contraseña') }}
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" x-transition class="max-w-xl mt-4">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div x-data="{ open: false }" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <button @click="open = !open" class="w-full flex justify-between items-center text-lg font-semibold text-red-600 focus:outline-none">
                    {{ __('Eliminar cuenta') }}
                    <svg :class="{'rotate-180': open}" class="h-5 w-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" x-transition class="max-w-xl mt-4">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
