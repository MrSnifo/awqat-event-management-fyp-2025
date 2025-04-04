document.addEventListener('DOMContentLoaded', () => {
    // Initialize modules
    Events.init();
    Filters.init();
    
    // Common event handlers
    document.querySelectorAll('.recommended-event').forEach(event => {
        event.addEventListener('click', function(e) {
            e.preventDefault();
            const eventId = Math.floor(1000 + Math.random() * 9000);
            window.location.href = `event/${eventId}`;
        });
    });
});