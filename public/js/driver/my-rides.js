document.addEventListener('DOMContentLoaded', () => {
    // Broadcast location updates periodically if there's any active ride
    
    // Check if configuration exists
    if (!window.config) return;

    let activeRidesExist = window.config.activeRidesExist;
    
    if (activeRidesExist && "geolocation" in navigator && window.config.locationUpdateRoute) {
        setInterval(() => {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                fetch(window.config.locationUpdateRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ lat: lat, lng: lng })
                }).catch(console.error);
            });
        }, 10000); // Send every 10 seconds
    }
});
