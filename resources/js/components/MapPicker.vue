<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

interface Props {
    modelValue?: { latitude: number | null; longitude: number | null };
    label?: string;
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Ubicación en el mapa',
});

const emit = defineEmits<{
    'update:modelValue': [value: { latitude: number | null; longitude: number | null }];
}>();

const mapContainer = ref<HTMLDivElement | null>(null);
let map: L.Map | null = null;
let marker: L.Marker | null = null;

// Coordenadas por defecto (Colombia - Bogotá)
const defaultLat = 4.7110;
const defaultLng = -74.0721;

const currentLat = ref<number | null>(props.modelValue?.latitude || null);
const currentLng = ref<number | null>(props.modelValue?.longitude || null);

// Buscador
const searchQuery = ref('');
const searchResults = ref<Array<{
    display_name: string;
    lat: string;
    lon: string;
}>>([]);
const isSearching = ref(false);
const showResults = ref(false);
let searchTimeout: ReturnType<typeof setTimeout> | null = null;

// Icono personalizado del marcador
const customIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

const initMap = () => {
    if (!mapContainer.value || map) return;

    const lat = currentLat.value || defaultLat;
    const lng = currentLng.value || defaultLng;

    // Crear el mapa
    map = L.map(mapContainer.value).setView([lat, lng], 13);

    // Agregar capa de tiles (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Agregar marcador si hay coordenadas
    if (currentLat.value && currentLng.value) {
        marker = L.marker([currentLat.value, currentLng.value], { 
            icon: customIcon,
            draggable: true 
        }).addTo(map);

        marker.on('dragend', (e) => {
            const position = e.target.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    }

    // Evento de clic en el mapa
    map.on('click', (e: L.LeafletMouseEvent) => {
        const { lat, lng } = e.latlng;
        updateCoordinates(lat, lng);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { 
                icon: customIcon,
                draggable: true 
            }).addTo(map!);

            marker.on('dragend', (e) => {
                const position = e.target.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }
    });
};

const updateCoordinates = (lat: number, lng: number) => {
    currentLat.value = lat;
    currentLng.value = lng;
    emit('update:modelValue', { 
        latitude: lat, 
        longitude: lng 
    });
};

const clearLocation = () => {
    currentLat.value = null;
    currentLng.value = null;
    if (marker && map) {
        map.removeLayer(marker);
        marker = null;
    }
    emit('update:modelValue', { 
        latitude: null, 
        longitude: null 
    });
};

const getCurrentLocation = () => {
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                updateCoordinates(lat, lng);
                
                if (map) {
                    map.setView([lat, lng], 15);
                    
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng], { 
                            icon: customIcon,
                            draggable: true 
                        }).addTo(map);

                        marker.on('dragend', (e) => {
                            const position = e.target.getLatLng();
                            updateCoordinates(position.lat, position.lng);
                        });
                    }
                }
            },
            (error) => {
                console.error('Error obteniendo ubicación:', error);
                alert('No se pudo obtener tu ubicación actual');
            }
        );
    } else {
        alert('Tu navegador no soporta geolocalización');
    }
};

const searchLocation = async () => {
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        showResults.value = false;
        return;
    }

    isSearching.value = true;
    showResults.value = false;

    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery.value)}&limit=5&countrycodes=co`
        );
        
        const data = await response.json();
        searchResults.value = data;
        showResults.value = true;
    } catch (error) {
        console.error('Error buscando ubicación:', error);
        searchResults.value = [];
        showResults.value = false;
    } finally {
        isSearching.value = false;
    }
};

// Búsqueda con debounce
const debouncedSearch = () => {
    // Limpiar el timeout anterior
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    // Si el campo está vacío, limpiar resultados
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        showResults.value = false;
        return;
    }

    // Establecer nuevo timeout
    searchTimeout = setTimeout(() => {
        searchLocation();
    }, 500); // 0.5 segundos
};

const selectSearchResult = (result: { display_name: string; lat: string; lon: string }) => {
    const lat = parseFloat(result.lat);
    const lng = parseFloat(result.lon);

    updateCoordinates(lat, lng);

    if (map) {
        map.setView([lat, lng], 15);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { 
                icon: customIcon,
                draggable: true 
            }).addTo(map);

            marker.on('dragend', (e) => {
                const position = e.target.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }
    }

    showResults.value = false;
    searchQuery.value = '';
};

// Cerrar resultados al hacer clic fuera
const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    if (!target.closest('.search-container')) {
        showResults.value = false;
    }
};

watch(() => props.modelValue, (newValue) => {
    if (newValue?.latitude && newValue?.longitude) {
        currentLat.value = newValue.latitude;
        currentLng.value = newValue.longitude;
        
        if (map) {
            map.setView([newValue.latitude, newValue.longitude], 13);
            
            if (marker) {
                marker.setLatLng([newValue.latitude, newValue.longitude]);
            } else {
                marker = L.marker([newValue.latitude, newValue.longitude], { 
                    icon: customIcon,
                    draggable: true 
                }).addTo(map);

                marker.on('dragend', (e) => {
                    const position = e.target.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });
            }
        }
    }
}, { deep: true });

onMounted(() => {
    initMap();
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    if (map) {
        map.remove();
    }
});
</script>

<template>
    <div class="map-picker">
        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
            {{ label }}
        </label>
        
        <!-- Buscador de ubicaciones -->
        <div class="mb-3 relative search-container">
            <div class="relative">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Buscar dirección o lugar en Colombia..."
                    @input="debouncedSearch"
                    class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-gray-200"
                />
                <i 
                    v-if="!isSearching"
                    class="pi pi-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                ></i>
                <i 
                    v-else
                    class="pi pi-spin pi-spinner absolute right-3 top-1/2 -translate-y-1/2 text-blue-600"
                ></i>
            </div>

            <!-- Resultados de búsqueda -->
            <div 
                v-if="showResults && searchResults.length > 0" 
                class="absolute z-[9999] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto"
            >
                <button
                    v-for="(result, index) in searchResults"
                    :key="index"
                    type="button"
                    @click="selectSearchResult(result)"
                    class="w-full text-left px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 last:border-b-0 transition-colors"
                >
                    <div class="flex items-start gap-2">
                        <i class="pi pi-map-marker text-blue-600 dark:text-blue-400 mt-1"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ result.display_name }}</span>
                    </div>
                </button>
            </div>

            <div 
                v-if="isSearching && searchQuery.trim()" 
                class="absolute z-[9999] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg p-4"
            >
                <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <i class="pi pi-spin pi-spinner"></i>
                    <span>Buscando ubicaciones...</span>
                </div>
            </div>

            <div 
                v-if="showResults && searchResults.length === 0 && !isSearching" 
                class="absolute z-[9999] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg p-4"
            >
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                    No se encontraron resultados para "{{ searchQuery }}"
                </p>
            </div>
        </div>

        <div class="mb-2 flex gap-2 flex-wrap">
            <button
                type="button"
                @click="getCurrentLocation"
                class="px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
            >
                <i class="pi pi-map-marker mr-1"></i>
                Usar mi ubicación
            </button>
            
            <button
                v-if="currentLat && currentLng"
                type="button"
                @click="clearLocation"
                class="px-3 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
            >
                <i class="pi pi-times mr-1"></i>
                Limpiar ubicación
            </button>
        </div>

        <div 
            ref="mapContainer" 
            class="h-[400px] w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 mb-2"
        ></div>

        <div v-if="currentLat && currentLng" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 p-3 rounded">
            <p class="font-semibold mb-1">Coordenadas seleccionadas:</p>
            <p><strong>Latitud:</strong> {{ currentLat.toFixed(6) }}</p>
            <p><strong>Longitud:</strong> {{ currentLng.toFixed(6) }}</p>
        </div>
        <div v-else class="text-sm text-gray-500 dark:text-gray-400 italic">
            Haz clic en el mapa para seleccionar una ubicación
        </div>
    </div>
</template>

<style scoped>
.map-picker {
    @apply w-full;
}

/* Estilos para el mapa en modo oscuro */
:deep(.leaflet-container) {
    background: #f3f4f6;
}

/* :deep(.leaflet-popup-content-wrapper) {
    @apply bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100;
} */
</style>
