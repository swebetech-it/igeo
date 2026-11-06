// Button click debugging
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listeners to all buttons with wire:click attributes
    const livewireButtons = document.querySelectorAll('button[wire\\:click]');
    
    livewireButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Button clicked:', {
                element: this,
                wireClick: this.getAttribute('wire:click'),
                text: this.textContent.trim(),
                id: this.id || 'no-id'
            });
            
            // Prevent default only for testing
            // e.preventDefault();
        });
    });
    
    console.log('Button debug script loaded. Found', livewireButtons.length, 'Livewire buttons');
});