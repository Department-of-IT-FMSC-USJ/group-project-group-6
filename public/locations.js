/*
 Leaflet-powered locations script
 - Initializes map centered on Sri Lanka
 - Adds markers from a local `locations` array (replace with API later)
 - Synchronizes side list with markers
 - Implements search and geolocation centering
*/
document.addEventListener('DOMContentLoaded', () => {
    // Sample location data with lat/lng
    const locations = [
        { id: 1, name: 'Colombo Recycling Hub', type: 'recycling', district: 'colombo', address: '123 Main St, Colombo', phone: '+94 11 123 4567', hours: 'Mon-Fri: 8AM-6PM', plastics: ['PET', 'HDPE'], lat: 6.9271, lng: 79.8612 },
        { id: 2, name: 'Kandy Drop-off Center', type: 'drop-off', district: 'kandy', address: '456 Hill Rd, Kandy', phone: '+94 81 234 5678', hours: 'Mon-Sat: 9AM-5PM', plastics: ['PP', 'Mixed'], lat: 7.2906, lng: 80.6337 },
        { id: 3, name: 'Galle Community Point', type: 'community', district: 'galle', address: '12 Fort St, Galle', phone: '+94 91 555 1234', hours: 'Daily: 9AM-4PM', plastics: ['Mixed'], lat: 6.0320, lng: 80.2170 }
    ];

    // Initialize map
    const map = L.map('location-map', { scrollWheelZoom: false }).setView([7.8731, 80.7718], 7);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker groups by type (for potential filtering)
    const markerGroup = L.layerGroup().addTo(map);
    const markersById = {};

    function getIconForType(type) {
        const color = type === 'recycling' ? 'green' : type === 'drop-off' ? 'blue' : type === 'pickup' ? 'orange' : 'purple';
        return L.divIcon({ className: 'custom-marker', html: `<span style="background:${color}" class="marker-pin"></span>` , iconSize: [18, 18], iconAnchor: [9, 18]});
    }

    // Add markers and populate side list
    const listContainer = document.getElementById('locations-container');
    const resultsCount = document.getElementById('results-count');

    function renderList(items) {
        listContainer.innerHTML = items.map(loc => `
            <div class="location-item" data-id="${loc.id}">
                <h4>${loc.name}</h4>
                <p>${loc.address}</p>
                <p>Type: ${loc.type}</p>
                <p>Phone: ${loc.phone}</p>
                <p>Hours: ${loc.hours}</p>
                <div class="location-actions">
                    <button class="btn btn-primary btn-sm" data-id="${loc.id}">View Details</button>
                    <button class="btn btn-secondary btn-xs pickup-btn" data-id="${loc.id}" style="display:none;">Request Pickup</button>
                </div>
            </div>
        `).join('');

        resultsCount.textContent = `Showing ${items.length} locations`;

        // Wire up list buttons
        listContainer.querySelectorAll('button[data-id]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(btn.getAttribute('data-id'));
                const loc = locations.find(l => l.id === id);
                if (loc) {
                    map.setView([loc.lat, loc.lng], 14);
                    markersById[id].openPopup();
                }
            });
        });

        // Wire up pickup buttons (only visible in pickup mode)
        listContainer.querySelectorAll('.pickup-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(btn.getAttribute('data-id'));
                showPickupModalForLocation(id);
            });
        });
    }

    function addMarkers(items) {
        markerGroup.clearLayers();
        items.forEach(loc => {
            const m = L.marker([loc.lat, loc.lng], { icon: getIconForType(loc.type) })
                .bindPopup(`<strong>${loc.name}</strong><br>${loc.address}<br><small>${loc.hours}</small>`)
                .addTo(markerGroup);
            markersById[loc.id] = m;
        });
    }

    // Initial render
    addMarkers(locations);
    renderList(locations);
    // Show pickup buttons if pickup mode is active
    updatePickupButtonsVisibility();
    // Handle direct location preselect
    handleDirectLocationParam();

    // Search locations
    window.searchLocations = function() {
        const searchInput = (document.getElementById('location-search').value || '').toLowerCase();
        const districtFilter = document.getElementById('district-filter').value;
        const typeFilter = document.getElementById('type-filter').value;

        const filtered = locations.filter(location => {
            return (!searchInput || location.name.toLowerCase().includes(searchInput) || location.address.toLowerCase().includes(searchInput)) &&
                   (!districtFilter || location.district === districtFilter) &&
                   (!typeFilter || location.type === typeFilter);
        });

        addMarkers(filtered);
    renderList(filtered);
    // ensure pickup buttons visibility persists after search/filter
    updatePickupButtonsVisibility();

        // Fit map to markers if any
        const group = new L.featureGroup(Object.values(markersById).filter(m => filtered.some(f => markersById[f.id] === m)));
        if (filtered.length > 0) {
            const bounds = L.latLngBounds(filtered.map(f => [f.lat, f.lng]));
            map.fitBounds(bounds.pad(0.3));
        }
    };

    // Geolocation: center map on user's location and show nearby markers
    window.getCurrentLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.setView([lat, lng], 13);
                L.circle([lat, lng], { radius: 500 }).addTo(map);
            }, err => {
                console.error('Geolocation error', err);
                alert('Unable to access location. Please allow location access.');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    };

    // Show location details modal (reuse existing function signature)
    window.showLocationDetails = function(locationId) {
        const location = locations.find(loc => loc.id === locationId);
        const modal = document.getElementById('locationDetailsModal');
        const content = document.getElementById('locationDetailsContent');
        if (location && modal && content) {
            content.innerHTML = `
                <h2>${location.name}</h2>
                <p><strong>Address:</strong> ${location.address}</p>
                <p><strong>Type:</strong> ${location.type}</p>
                <p><strong>District:</strong> ${location.district}</p>
                <p><strong>Phone:</strong> ${location.phone}</p>
                <p><strong>Hours:</strong> ${location.hours}</p>
                <p><strong>Accepted Plastics:</strong> ${location.plastics.join(', ')}</p>
            `;
            modal.classList.add('show');
        }
    };

    // Pickup-mode helpers
    function queryString() {
        return window.location.search || '';
    }

    function isPickupMode() {
        const qs = new URLSearchParams(queryString());
        return qs.get('pickup') === 'true' || qs.has('pickup');
    }

    function updatePickupButtonsVisibility() {
        const pickupMode = isPickupMode();
        // show/hide pickup buttons in the list
        document.querySelectorAll('.pickup-btn').forEach(b => {
            b.style.display = pickupMode ? '' : 'none';
        });
        // update marker popups to include a request button when in pickup mode
        if (pickupMode) {
            // rebind popups with pickup action link
            Object.values(markersById).forEach(marker => {
                const locId = Object.keys(markersById).find(k => markersById[k] === marker);
                const loc = locations.find(l => String(l.id) === String(locId));
                if (loc) {
                    marker.bindPopup(`<strong>${loc.name}</strong><br>${loc.address}<br><small>${loc.hours}</small><br><button class="btn btn-secondary btn-xs" onclick="showPickupModalForLocation(${loc.id})">Request Pickup</button>`);
                }
            });
        }
    }

    // If a specific location id is provided via ?loc=ID, open its details or pickup modal
    function handleDirectLocationParam() {
        const qs = new URLSearchParams(queryString());
        const locParam = qs.get('loc');
        if (!locParam) return;
        const id = parseInt(locParam, 10);
        const loc = locations.find(l => l.id === id);
        if (!loc) return;

        // center map and open popup
        map.setView([loc.lat, loc.lng], 14);
        // ensure markers have been created and stored
        const marker = markersById[id];
        if (marker) marker.openPopup();

        // If pickup mode requested, open pickup modal directly
        if (isPickupMode()) {
            // small timeout to ensure modal elements are present and visible
            setTimeout(() => showPickupModalForLocation(id), 250);
        } else {
            // open details modal after a brief delay so UI feels responsive
            setTimeout(() => window.showLocationDetails(id), 250);
        }
    }

    // Show pickup modal prefilled with location id
    window.showPickupModalForLocation = function(locationId) {
        const modal = document.getElementById('pickupRequestModal');
        const input = document.getElementById('pickup-location-id');
        if (modal && input) {
            input.value = locationId;
            modal.classList.add('show');
            // focus the weight field for quick entry
            setTimeout(() => {
                const weightInput = document.getElementById('pickup-weight');
                if (weightInput) weightInput.focus();
            }, 120);
        }
    };

    window.closePickupRequestModal = function() {
        const modal = document.getElementById('pickupRequestModal');
        if (modal) modal.classList.remove('show');
    };

    window.submitPickupRequest = function(event) {
        event.preventDefault();
        const form = event.target;
        const locationId = form.locationId.value;
        const name = form.name.value;
        const phone = form.phone.value;
        const weight = parseFloat(form.weight ? form.weight.value : 0);
        const date = form.date.value;
        const notes = form.notes.value;

        if (!name || !phone) { alert('Please provide your name and phone.'); return; }
        if (!weight || weight <= 0) { alert('Please provide an estimated weight greater than 0.'); return; }

        // Simulate sending pickup request (replace with API call)
        console.log('Pickup request', { locationId, name, phone, weight, date, notes });
        closePickupRequestModal();
        alert('Pickup request sent. The location will contact you to confirm details.');
    };

    // Modal close helpers
    window.closeLocationDetailsModal = function() {
        const modal = document.getElementById('locationDetailsModal');
        if (modal) modal.classList.remove('show');
    };

    window.showAddLocationModal = function() {
        const modal = document.getElementById('addLocationModal');
        if (modal) modal.classList.add('show');
    };

    window.closeAddLocationModal = function() {
        const modal = document.getElementById('addLocationModal');
        if (modal) modal.classList.remove('show');
    };

    // Submit add location (existing placeholder behavior)
    window.submitLocationForm = function(event) {
        event.preventDefault();
        const form = event.target;
        // basic extraction
        const locationData = {
            id: Date.now(),
            name: form.locationName.value,
            type: form.locationType.value,
            address: form.locationAddress.value,
            district: form.locationDistrict.value,
            phone: form.locationPhone.value,
            hours: form.operatingHours.value,
            plastics: Array.from(form.querySelectorAll('input[name="acceptedPlastics"]')).filter(i=>i.checked).map(i=>i.value),
            description: form.locationDescription.value,
            terms: form.locationTerms.checked,
            lat: null,
            lng: null
        };

        if (!locationData.terms) { alert('You must agree to the terms.'); return; }
        if (locationData.plastics.length === 0) { alert('Please select at least one plastic type.'); return; }

        // If geocoding is available, we could attempt to geocode address to lat/lng.
        // For now we push to local array (no persistence) and refresh list.
        locations.push(locationData);
        renderList(locations);
        closeAddLocationModal();
        alert('Location submitted. It will appear on the map after approval in the real system.');
    };
});