<div x-data="{ show: false, message: '', type: 'success' }"
     x-on:toast.window="
        show = true; 
        message = $event.detail.message; 
        type = $event.detail.type; 
        setTimeout(() => { show = false }, 4000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform translate-x-full opacity-0"
     x-transition:enter-end="transform translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="transform translate-x-0 opacity-100"
     x-transition:leave-end="transform translate-x-full opacity-0"
     class="fixed top-4 right-4 z-50">
    <div :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
        'bg-yellow-500': type === 'warning',
    }" class="text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3 min-w-[300px] border border-white/10">
        <div class="shrink-0">
            <svg x-show="type === 'success'" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg x-show="type === 'error'" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <svg x-show="type === 'warning'" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-base font-medium" x-text="message"></p>
        </div>
        <button @click="show = false" class="shrink-0 hover:opacity-75 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div> 