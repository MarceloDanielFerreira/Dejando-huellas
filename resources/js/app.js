import './bootstrap';
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Configuración de Alpine.js para notificaciones toast
document.addEventListener('alpine:init', () => {
    Alpine.data('notifications', () => ({
        notifications: [],
        add(message, type = 'success') {
            this.notifications.push({
                id: Date.now(),
                message,
                type
            });
            setTimeout(() => {
                this.remove(this.notifications[0].id);
            }, 3000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(notification => notification.id !== id);
        }
    }));
});

// Escuchar el evento toast desde Livewire
Livewire.on('toast', ({ message, type }) => {
    const notifications = Alpine.$data.get('notifications');
    if (notifications) {
        notifications.add(message, type);
    }
});
