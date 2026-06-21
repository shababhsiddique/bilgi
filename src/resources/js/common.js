import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

console.log('resource/js/common.js loaded with vite');

// Create an instance of Notyf
window.notyf = new Notyf({
    duration: 4000, // Increased default duration for better UX
    position: {
        x: 'right',
        y: 'top',
    },
    dismissible: true, // Allow all notifications to be dismissible by default
    types: [
        {
            type: 'success',
            background: '#10b981', // Tailwind green-500
        },
        {
            type: 'warning',
            background: '#f59e0b', // Tailwind yellow-500
        },
        {
            type: 'error',
            background: '#f54a00', // orange — error (kept distinct from brand rose & amber warning)
        },
        {
            type: 'info',
            background: '#3b82f6', // Tailwind blue-500
        }
    ]
});

// Toast confirmation whenever an item is added to the cart.
// Dispatched server-side via $this->dispatch('cart-item-added', name: '...').
document.addEventListener('livewire:init', () => {
    Livewire.on('cart-item-added', (event) => {
        const name = event?.name || (Array.isArray(event) ? event[0]?.name : null);
        window.notyf.success(name ? `${name} added to cart` : 'Added to cart');
    });
});
