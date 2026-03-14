document.addEventListener('DOMContentLoaded', function () {
    let knownRideIds = window.config.knownRideIds || [];

    function fetchAvailableRides() {
        fetch(window.config.pollAvailableRoute)
            .then(res => res.json())
            .then(data => {
                if (data.rides) {
                    data.rides.reverse().forEach(ride => {
                        // Only add if we haven't seen this ride yet
                        if (!knownRideIds.includes(ride.id)) {
                            knownRideIds.push(ride.id);
                            addNewRideCard(ride);
                        }
                    });
                    
                    // Remove rides that are no longer pending (accepted or cancelled)
                    const currentIds = data.rides.map(r => r.id);
                    knownRideIds = knownRideIds.filter(id => {
                        if (!currentIds.includes(id)) {
                            const card = document.getElementById('ride-card-' + id);
                            if (card) card.remove();
                            return false; // remove from known
                        }
                        return true; // keep
                    });

                    // Show/hide empty state
                    const noRidesMsg = document.getElementById('no-rides-message');
                    const container = document.getElementById('rides-container');
                    
                    if (currentIds.length > 0 && noRidesMsg) {
                        noRidesMsg.style.display = 'none';
                    } else if (currentIds.length === 0) {
                        if (noRidesMsg) {
                            noRidesMsg.style.display = 'block';
                        } else {
                            container.innerHTML = `
                                <div id="no-rides-message" class="col-span-1 md:col-span-2 lg:col-span-3">
                                    <div class="bg-white border border-neutral-200 rounded-3xl p-16 text-center shadow-sm flex flex-col items-center">
                                        <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                                            <div class="relative flex h-8 w-8">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-40"></span>
                                              <span class="relative inline-flex rounded-full h-8 w-8 bg-emerald-500"></span>
                                            </div>
                                        </div>
                                        <h3 class="text-2xl font-bold font-heading text-neutral-900 mb-2">Searching for nearby riders...</h3>
                                        <p class="text-neutral-500 font-medium max-w-sm mx-auto">Keep this page open. New ride requests in your vicinity will appear here instantly.</p>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }
            })
            .catch(console.error);
    }

    function addNewRideCard(ride) {
        const container = document.getElementById('rides-container');
        const noRidesMsg = document.getElementById('no-rides-message');
        if (noRidesMsg) noRidesMsg.style.display = 'none';

        const formatPrice = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(ride.estimated_fare);
        const formattedId = ride.id.toString().padStart(5, '0');
        const customerName = ride.customer ? ride.customer.name : 'Guest';
        const distance = parseFloat(ride.distance_km).toFixed(2);
        const pickupDist = ride.pickup_distance_km !== null && ride.pickup_distance_km !== undefined
            ? parseFloat(ride.pickup_distance_km).toFixed(2)
            : null;

        const pickupDistHTML = pickupDist
            ? `<div class="flex items-center justify-between bg-blue-50 border border-blue-100 rounded-xl px-3 py-2.5 highlight-border">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                        <p class="text-[10px] font-black tracking-widest uppercase text-blue-600">Your drive to pickup</p>
                    </div>
                    <p class="text-sm font-bold font-mono text-blue-700">${pickupDist} km away</p>
               </div>`
            : `<div class="flex items-center gap-2 bg-neutral-50 border border-dashed border-neutral-200 rounded-xl px-3 py-2.5">
                    <p class="text-[10px] font-black tracking-widest uppercase text-neutral-400">Set location to see pickup distance</p>
               </div>`;
        
        // CSRF Token for the form
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const surgeMultiplierHTML = ride.surge_multiplier > 1 
            ? `<p class="text-[10px] font-black text-orange-500 mt-1">🔥 ${parseFloat(ride.surge_multiplier).toFixed(2).replace(/\.00$/, '')}x surge</p>`
            : '';

        const newCard = document.createElement('div');
        // Add highlighted "New" styling
        newCard.className = 'bg-emerald-50 border-2 border-emerald-400 rounded-3xl p-6 shadow-xl relative overflow-hidden flex flex-col group transform transition-all duration-1000 animate-[fade-in-down_0.5s_ease-out]';
        newCard.id = `ride-card-${ride.id}`;
        
        newCard.innerHTML = `
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/20 rounded-full blur-[30px] -mt-10 -mr-10 pointer-events-none highlight-badge"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 highlight-bar"></div>

            <div class="flex justify-between items-start mb-6 border-b border-emerald-200/50 pb-4 highlight-border">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold font-mono text-[10px] uppercase tracking-widest rounded mb-1 border border-emerald-300">
                        #${formattedId}
                    </span>
                    <p class="text-xs text-emerald-600 font-black animate-pulse new-label">NEW RIDE!</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black tracking-widest uppercase text-emerald-700 mb-0.5">Estimated Payout</p>
                    <p class="text-2xl font-black font-heading text-emerald-950 tracking-tighter">${formatPrice}</p>
                    ${surgeMultiplierHTML}
                </div>
            </div>

            <div class="relative pl-7 space-y-5 mb-auto">
                <div class="absolute left-[0.45rem] top-3 bottom-3 w-0.5 bg-emerald-200/50 highlight-border"></div>
                <div class="relative">
                    <div class="absolute -left-[2.1rem] w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-sm ring-1 ring-emerald-400/20"></div>
                    <p class="text-[10px] font-black text-emerald-700 tracking-widest uppercase mb-1">Pickup</p>
                    <p class="text-sm font-bold text-neutral-900 leading-snug line-clamp-2" title="${ride.pickup_address}">${ride.pickup_address}</p>
                </div>
                <div class="relative">
                    <div class="absolute -left-[2.1rem] w-4 h-4 bg-amber-400 border-[3px] border-white shadow-sm ring-1 ring-amber-400/20 transform rotate-45"></div>
                    <p class="text-[10px] font-black text-amber-600 tracking-widest uppercase mb-1">Drop-off</p>
                    <p class="text-sm font-bold text-neutral-900 leading-snug line-clamp-2" title="${ride.drop_address}">${ride.drop_address}</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-emerald-200/50 highlight-border space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 highlight-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black tracking-widest uppercase text-emerald-700 leading-none mb-0.5">Rider</p>
                            <p class="text-xs font-bold text-neutral-900">${customerName}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black tracking-widest uppercase text-emerald-700 leading-none mb-0.5">Trip Distance</p>
                        <p class="text-sm font-bold font-mono text-neutral-900">${distance} km</p>
                    </div>
                </div>
                ${pickupDistHTML}
            </div>

            <form method="POST" action="/driver/rides/${ride.id}/accept" class="mt-4">
                <input type="hidden" name="_token" value="${csrfToken}">
                <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all hover:-translate-y-0.5 active:scale-[0.98] flex items-center justify-center gap-2 highlight-btn">
                    Accept Ride
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </button>
            </form>
        `;
        
        container.prepend(newCard);

        // Revert back to normal styling after 10 seconds
        setTimeout(() => {
            if(document.getElementById(`ride-card-${ride.id}`)) {
                // Update main card classes
                newCard.className = 'bg-white border border-neutral-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden flex flex-col group';
                
                // Remove highlight specific elements
                newCard.querySelector('.highlight-badge').remove();
                newCard.querySelector('.highlight-bar').remove();
                newCard.querySelector('.new-label').remove();
                
                // Update borders
                newCard.querySelectorAll('.highlight-border').forEach(el => {
                    el.classList.remove('border-emerald-200/50', 'bg-emerald-200/50');
                    if(el.classList.contains('border-b') || el.classList.contains('border-t')) {
                        el.classList.add('border-neutral-100');
                    } else {
                        el.classList.add('bg-neutral-200'); // for the vertical line
                    }
                });

                // Update Badge
                const badge = newCard.querySelector('span.bg-emerald-100');
                if(badge) {
                    badge.className = 'inline-block px-2 py-0.5 bg-neutral-100 text-neutral-500 font-bold font-mono text-[10px] uppercase tracking-widest rounded mb-1 border border-neutral-200';
                }

                // Update texts
                newCard.querySelectorAll('.text-emerald-700').forEach(el => {
                    el.classList.replace('text-emerald-700', 'text-neutral-400');
                    if(el.classList.contains('text-[10px]')) {
                        el.classList.replace('text-emerald-600', 'text-neutral-400'); // For Estimare Payout label
                    }
                });
                 newCard.querySelectorAll('.text-emerald-600').forEach(el => {
                    el.classList.replace('text-emerald-600', 'text-neutral-400'); // For Pickup label
                });
                newCard.querySelectorAll('.text-emerald-950').forEach(el => {
                    el.classList.replace('text-emerald-950', 'text-neutral-900');
                });

                // Update Icon circle
                const iconCircle = newCard.querySelector('.highlight-icon');
                if(iconCircle) {
                    iconCircle.className = 'w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-500';
                }

                // Update Button
                const btn = newCard.querySelector('.highlight-btn');
                if(btn) {
                    btn.className = 'w-full py-3.5 bg-neutral-900 hover:bg-neutral-800 text-white border border-neutral-800 font-bold rounded-xl shadow-lg shadow-neutral-900/20 transition-all hover:-translate-y-0.5 active:scale-[0.98] flex items-center justify-center gap-2';
                }
            }
        }, 10000);
    }

    // Poll every 5 seconds for snappy updates
    setInterval(fetchAvailableRides, 5000);

    // Broadast location while waiting for a ride
    if ("geolocation" in navigator && window.config.locationUpdateRoute) {
        setInterval(() => {
            navigator.geolocation.getCurrentPosition((position) => {
                fetch(window.config.locationUpdateRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        lat: position.coords.latitude, 
                        lng: position.coords.longitude 
                    })
                }).catch(console.error);
            });
        }, 10000);
    }
});
