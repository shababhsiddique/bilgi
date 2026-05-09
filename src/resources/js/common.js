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
            background: '#ee3f38', // Your custom brandRed color
        },
        {
            type: 'info',
            background: '#3b82f6', // Tailwind blue-500
        }
    ]
});
