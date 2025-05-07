@push('scripts')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapComponent', () => ({
                map: null,
                marker: null,
                selectedAddress: 'None',

                initMap() {
                    const barquisimeto = [10.0670, -69.3467];
                    
                    this.map = L.map(this.$refs.mapContainer).setView(barquisimeto, 13);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.map);

                    this.marker = L.marker(barquisimeto)
                        .addTo(this.map)
                        .bindPopup('Click on the map to select your location')
                        .openPopup();

                    this.map.on('click', (e) => {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;

                        if (this.marker) {
                            this.map.removeLayer(this.marker);
                        }

                        this.marker = L.marker([lat, lng]).addTo(this.map);
                        this.updateFormFields(lat, lng);
                        this.getAddressFromCoordinates(lat, lng);
                    });
                },

                updateFormFields(lat, lng) {
                    const latitudeInput = document.querySelector('[name="data[latitude]"]');
                    const longitudeInput = document.querySelector('[name="data[longitude]"]');

                    if (latitudeInput) {
                        latitudeInput.value = lat.toFixed(8);
                        latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    if (longitudeInput) {
                        longitudeInput.value = lng.toFixed(8);
                        longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    // Update Livewire state
                    if (window.Livewire) {
                        window.Livewire.find(this.$wire.id).set('data.latitude', lat.toFixed(8));
                        window.Livewire.find(this.$wire.id).set('data.longitude', lng.toFixed(8));
                    }
                },

                async getAddressFromCoordinates(lat, lng) {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
                        );
                        const data = await response.json();
                        
                        if (data.display_name) {
                            const locationName = data.address.city || 
                                               data.address.town || 
                                               data.address.village || 
                                               data.address.suburb || 
                                               'Selected Location';

                            const addressInput = document.querySelector('[name="data[address]"]');
                            const nameInput = document.querySelector('[name="data[name]"]');

                            if (addressInput) {
                                addressInput.value = data.display_name;
                                addressInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }

                            if (nameInput) {
                                nameInput.value = locationName;
                                nameInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }

                            // Update Livewire state
                            if (window.Livewire) {
                                window.Livewire.find(this.$wire.id).set('data.address', data.display_name);
                                window.Livewire.find(this.$wire.id).set('data.name', locationName);
                            }

                            if (this.marker) {
                                this.marker.bindPopup(data.display_name).openPopup();
                            }

                            this.selectedAddress = data.display_name;
                        }
                    } catch (error) {
                        console.error('Error getting address:', error);
                    }
                }
            }));
        });
    </script>
@endpush

<div 
    x-data="mapComponent"
    x-init="$nextTick(() => initMap())"
    class="space-y-4"
    wire:ignore
>
    <div class="p-4 bg-primary-50 dark:bg-primary-900 rounded-lg">
        <p class="text-sm text-primary-600 dark:text-primary-400">
            Click on the map to select your address location. The form will be automatically filled with the selected location data.
        </p>
    </div>

    <div class="w-full rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700">
        <div x-ref="mapContainer" style="height: 500px;"></div>
    </div>

    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Selected location: ' + selectedAddress"></p>
    </div>
</div> 