<div 
    x-data="{ show: false, message: '', type: 'success' }"
    x-on:toast.window="
        show = true; 
        message = $event.detail.message; 
        type = $event.detail.type; 
        setTimeout(() => { show = false }, 5000)
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-8"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-8"
    class="fixed top-4 right-4 z-[60] flex justify-end w-full max-w-[calc(100%-2rem)]"
    style="pointer-events: none;"
>
    <div 
        :class="{
            'from-green-500/20 to-green-600/20 text-green-700': type === 'success',
            'from-red-500/20 to-red-600/20 text-red-700': type === 'error',
            'from-yellow-500/20 to-yellow-600/20 text-yellow-700': type === 'warning'
        }" 
        class="pointer-events-auto flex items-center gap-3 rounded-lg px-6 py-4 shadow-[0_4px_12px_rgba(0,0,0,0.15)] ring-1 ring-black/5 bg-gradient-to-r backdrop-blur-md"
        style="min-width: 350px;"
        <!-- Icono -->
        <div class="shrink-0">
            <template x-if="type === 'success'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </template>
            <template x-if="type === 'warning'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </template>
        </div>
        
        <!-- Mensaje -->
        <div class="flex-1">
            <p class="text-sm font-medium tracking-wide" x-text="message"></p>
        </div>

        <!-- Botón cerrar -->
        <button 
            @click="show = false" 
            class="shrink-0 rounded-lg p-1 transition hover:bg-black/5 focus:outline-none"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<style>
.toast-enter-active, .toast-leave-active {
    transition: all 0.3s ease;
}
.toast-enter-from, .toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>