<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

interface Sucursal {
    id: string;
    name: string;
    address: string;
    latitude?: number | null;
    longitude?: number | null;
}

interface Props {
    modelValue?: { latitude: number | null; longitude: number | null };
    label?: string;
    sucursales?: Sucursal[];
    currentSucursalId?: string;
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Ubicación en el mapa',
    sucursales: () => [],
});

const emit = defineEmits<{
    'update:modelValue': [value: { latitude: number | null; longitude: number | null }];
    'update:address': [address: string];
}>();

const mapContainer = ref<HTMLDivElement | null>(null);
let map: L.Map | null = null;
let marker: L.Marker | null = null;
let otherMarkers: L.Marker[] = [];
const isFullscreen = ref(false);

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

// Información de la ubicación
const locationInfo = ref<{
    city?: string;
    state?: string;
    country?: string;
    address?: string;
} | null>(null);

// Icono personalizado del marcador (edificio) - Marcador principal (azul)
const customIcon = L.divIcon({
    html: '<i class="pi pi-building text-blue-600" style="font-size: 32px;"></i>',
    className: 'custom-building-marker',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
});

// Icono para otras sucursales (gris)
const otherSucursalIcon = L.divIcon({
    html: '<i class="pi pi-building text-gray-500" style="font-size: 28px;"></i>',
    className: 'custom-building-marker',
    iconSize: [28, 28],
    iconAnchor: [14, 28],
    popupAnchor: [0, -28]
});

// Función para crear el contenido del popup para otras sucursales
const createOtherSucursalPopup = (sucursal: Sucursal): string => {
    return `
        <div class="popup-content" style="min-width: 200px; max-width: 250px;">
            <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px; color: #6b7280; border-bottom: 2px solid #9ca3af; padding-bottom: 4px;">
                <i class="pi pi-building" style="margin-right: 4px;"></i>
                ${sucursal.name}
            </div>
            
            <div style="margin-bottom: 6px; padding: 6px; background: #f9fafb; border-radius: 4px;">
                <div style="font-size: 10px; color: #6b7280; margin-bottom: 2px;">
                    <i class="pi pi-map-marker" style="margin-right: 4px;"></i>Dirección
                </div>
                <div style="font-size: 11px; color: #374151;">${sucursal.address}</div>
            </div>
            
            <div style="font-size: 10px; color: #9ca3af; margin-top: 6px; padding: 4px; background: #f3f4f6; border-radius: 3px;">
                <div style="margin-bottom: 2px;">
                    <i class="pi pi-compass" style="margin-right: 3px;"></i>
                    <strong>Lat:</strong> ${sucursal.latitude}
                </div>
                <div>
                    <i class="pi pi-compass" style="margin-right: 3px;"></i>
                    <strong>Lng:</strong> ${sucursal.longitude}
                </div>
            </div>
            
            <div style="font-size: 9px; color: #9ca3af; font-style: italic; text-align: center; padding-top: 4px; margin-top: 4px; border-top: 1px solid #e5e7eb;">
                <i class="pi pi-info-circle" style="margin-right: 2px;"></i>
                Otra sucursal registrada
            </div>
        </div>
    `;
};

// Función para crear el contenido del popup
const createPopupContent = (lat: number, lng: number): string => {
    const info = locationInfo.value;
    
    let addressHTML = '';
    if (info) {
        if (info.address) {
            addressHTML += `
                <div style="margin-bottom: 6px; padding: 8px; background: #f3f4f6; border-radius: 4px;">
                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">
                        <i class="pi pi-map-marker" style="margin-right: 4px;"></i>Dirección
                    </div>
                    <div style="font-size: 12px; color: #374151;">${info.address}</div>
                </div>
            `;
        }
        
        if (info.city || info.state) {
            addressHTML += `
                <div style="margin-bottom: 6px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            `;
            
            if (info.city) {
                addressHTML += `
                    <div style="padding: 6px; background: #eff6ff; border-radius: 4px;">
                        <div style="font-size: 10px; color: #1e40af; margin-bottom: 2px;">
                            <i class="pi pi-building" style="margin-right: 2px;"></i>Ciudad
                        </div>
                        <div style="font-size: 11px; color: #1e3a8a; font-weight: 500;">${info.city}</div>
                    </div>
                `;
            }
            
            if (info.state) {
                addressHTML += `
                    <div style="padding: 6px; background: #f0fdf4; border-radius: 4px;">
                        <div style="font-size: 10px; color: #16a34a; margin-bottom: 2px;">
                            <i class="pi pi-flag" style="margin-right: 2px;"></i>Departamento
                        </div>
                        <div style="font-size: 11px; color: #15803d; font-weight: 500;">${info.state}</div>
                    </div>
                `;
            }
            
            addressHTML += `</div>`;
        }
    }
    
    return `
        <div class="popup-content" style="min-width: 250px; max-width: 300px;">
            <div style="font-weight: bold; font-size: 14px; margin-bottom: 10px; color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 6px;">
                <i class="pi pi-building" style="margin-right: 4px;"></i>
                Ubicación de Sucursal
            </div>
            
            ${addressHTML}
            
            <div style="font-size: 11px; color: #4b5563; margin-bottom: 6px; padding: 6px; background: #fef3c7; border-radius: 4px;">
                <div style="margin-bottom: 3px;">
                    <i class="pi pi-compass" style="margin-right: 4px; color: #d97706;"></i>
                    <strong>Lat:</strong> ${lat}
                </div>
                <div>
                    <i class="pi pi-compass" style="margin-right: 4px; color: #d97706;"></i>
                    <strong>Lng:</strong> ${lng}
                </div>
            </div>
            
            <div style="font-size: 10px; color: #6b7280; font-style: italic; text-align: center; padding-top: 6px; border-top: 1px solid #e5e7eb;">
                <i class="pi pi-info-circle" style="margin-right: 2px;"></i>
                Arrastra el marcador para ajustar
            </div>
        </div>
    `;
};

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

    // Agregar marcadores de otras sucursales
    addOtherSucursalesMarkers();

    // Agregar marcador si hay coordenadas
    if (currentLat.value && currentLng.value) {
        marker = L.marker([currentLat.value, currentLng.value], { 
            icon: customIcon,
            draggable: true 
        }).addTo(map);

        // Agregar popup al marcador
        marker.bindPopup(createPopupContent(currentLat.value, currentLng.value), {
            maxWidth: 280,
            minWidth: 250,
            maxHeight: 300,
            autoPan: true,
            autoPanPadding: [50, 50],
            keepInView: true,
            className: 'custom-popup'
        }).openPopup();

        marker.on('dragend', async (e) => {
            const position = e.target.getLatLng();
            await updateCoordinates(position.lat, position.lng);
            // Actualizar el contenido del popup después de arrastrar
            marker!.setPopupContent(createPopupContent(position.lat, position.lng));
        });
    }

    // Evento de clic en el mapa
    map.on('click', async (e: L.LeafletMouseEvent) => {
        const { lat, lng } = e.latlng;
        await updateCoordinates(lat, lng);

        if (marker) {
            marker.setLatLng([lat, lng]);
            marker.setPopupContent(createPopupContent(lat, lng)).openPopup();
        } else {
            marker = L.marker([lat, lng], { 
                icon: customIcon,
                draggable: true 
            }).addTo(map!);

                marker.bindPopup(createPopupContent(lat, lng), {
                    maxWidth: 280,
                    minWidth: 250,
                    maxHeight: 300,
                    autoPan: true,
                    autoPanPadding: [50, 50],
                    keepInView: true,
                    className: 'custom-popup'
                }).openPopup();            marker.on('dragend', async (e) => {
                const position = e.target.getLatLng();
                await updateCoordinates(position.lat, position.lng);
                marker!.setPopupContent(createPopupContent(position.lat, position.lng));
            });
        }
    });
};

// Función para obtener información de la ubicación (geocodificación inversa)
const fetchLocationInfo = async (lat: number, lng: number) => {
    try {
        const response = await fetch(
            `/geocoding/reverse?lat=${lat}&lng=${lng}`
        );
        
        const data = await response.json();
        
        if (data.address) {
            locationInfo.value = {
                city: data.address.city || data.address.town || data.address.village || data.address.municipality,
                state: data.address.state || data.address.region,
                country: data.address.country,
                address: data.display_name
            };
        }
    } catch (error) {
        console.error('Error obteniendo información de ubicación:', error);
        locationInfo.value = null;
    }
};

// Función para agregar marcadores de otras sucursales
const addOtherSucursalesMarkers = () => {
    if (!map || !props.sucursales) return;

    // Limpiar marcadores anteriores
    otherMarkers.forEach(marker => {
        if (map) {
            map.removeLayer(marker);
        }
    });
    otherMarkers = [];

    // Agregar marcadores para cada sucursal
    props.sucursales.forEach(sucursal => {
        // No mostrar la sucursal actual
        if (sucursal.id === props.currentSucursalId) return;
        
        // Solo mostrar si tiene coordenadas
        if (sucursal.latitude && sucursal.longitude) {
            const otherMarker = L.marker([sucursal.latitude, sucursal.longitude], {
                icon: otherSucursalIcon,
                draggable: false
            }).addTo(map!);

            otherMarker.bindPopup(createOtherSucursalPopup(sucursal), {
                maxWidth: 250,
                minWidth: 200,
                maxHeight: 250,
                autoPan: true,
                autoPanPadding: [50, 50],
                className: 'custom-popup other-sucursal-popup'
            });

            otherMarkers.push(otherMarker);
        }
    });
};

const updateCoordinates = async (lat: number, lng: number) => {
    currentLat.value = lat;
    currentLng.value = lng;
    emit('update:modelValue', { 
        latitude: lat, 
        longitude: lng 
    });
    
    // Obtener información de la ubicación
    await fetchLocationInfo(lat, lng);
    
    // Emitir la dirección si está disponible
    if (locationInfo.value?.address) {
        emit('update:address', locationInfo.value.address);
    }
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
            async (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                await updateCoordinates(lat, lng);
                
                if (map) {
                    map.setView([lat, lng], 15);
                    
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                        marker.setPopupContent(createPopupContent(lat, lng)).openPopup();
                    } else {
                        marker = L.marker([lat, lng], { 
                            icon: customIcon,
                            draggable: true 
                        }).addTo(map);

                        marker.bindPopup(createPopupContent(lat, lng), {
                            maxWidth: 350,
                            className: 'custom-popup'
                        }).openPopup();

                        marker.on('dragend', async (e) => {
                            const position = e.target.getLatLng();
                            await updateCoordinates(position.lat, position.lng);
                            marker!.setPopupContent(createPopupContent(position.lat, position.lng));
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
        // Obtener los límites actuales del mapa
        let viewboxParam = '';
        if (map) {
            const bounds = map.getBounds();
            const sw = bounds.getSouthWest(); // Suroeste
            const ne = bounds.getNorthEast(); // Noreste
            
            // Formato de viewbox para Nominatim: oeste,sur,este,norte (left,bottom,right,top)
            viewboxParam = `${sw.lng},${sw.lat},${ne.lng},${ne.lat}`;
        }

        const url = `/geocoding/search?query=${encodeURIComponent(searchQuery.value)}${viewboxParam ? `&viewbox=${viewboxParam}` : ''}`;
        
        const response = await fetch(url);
        
        const data = await response.json();
        searchResults.value = data;
        showResults.value = true;
    } catch (error) {
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

const selectSearchResult = async (result: { display_name: string; lat: string; lon: string }) => {
    const lat = parseFloat(result.lat);
    const lng = parseFloat(result.lon);

    await updateCoordinates(lat, lng);

    if (map) {
        map.setView([lat, lng], 15);

        if (marker) {
            marker.setLatLng([lat, lng]);
            marker.setPopupContent(createPopupContent(lat, lng)).openPopup();
        } else {
            marker = L.marker([lat, lng], { 
                icon: customIcon,
                draggable: true 
            }).addTo(map);

            marker.bindPopup(createPopupContent(lat, lng), {
                maxWidth: 280,
                minWidth: 250,
                maxHeight: 300,
                autoPan: true,
                autoPanPadding: [50, 50],
                keepInView: true,
                className: 'custom-popup'
            }).openPopup();

            marker.on('dragend', async (e) => {
                const position = e.target.getLatLng();
                await updateCoordinates(position.lat, position.lng);
                marker!.setPopupContent(createPopupContent(position.lat, position.lng));
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

// Función para pantalla completa
const toggleFullscreen = () => {
    isFullscreen.value = !isFullscreen.value;
    
    // Dar tiempo para que el CSS se aplique antes de invalidar el tamaño del mapa
    setTimeout(() => {
        if (map) {
            map.invalidateSize();
        }
    }, 100);
};

watch(() => props.modelValue, async (newValue) => {
    if (newValue?.latitude && newValue?.longitude) {
        currentLat.value = newValue.latitude;
        currentLng.value = newValue.longitude;
        
        // Obtener información de la ubicación
        await fetchLocationInfo(newValue.latitude, newValue.longitude);
        
        if (map) {
            map.setView([newValue.latitude, newValue.longitude], 13);
            
            if (marker) {
                marker.setLatLng([newValue.latitude, newValue.longitude]);
                marker.setPopupContent(createPopupContent(newValue.latitude, newValue.longitude)).openPopup();
            } else {
                marker = L.marker([newValue.latitude, newValue.longitude], { 
                    icon: customIcon,
                    draggable: true 
                }).addTo(map);

                marker.bindPopup(createPopupContent(newValue.latitude, newValue.longitude), {
                    maxWidth: 280,
                    minWidth: 250,
                    maxHeight: 300,
                    autoPan: true,
                    autoPanPadding: [50, 50],
                    keepInView: true,
                    className: 'custom-popup'
                }).openPopup();

                marker.on('dragend', async (e) => {
                    const position = e.target.getLatLng();
                    await updateCoordinates(position.lat, position.lng);
                    marker!.setPopupContent(createPopupContent(position.lat, position.lng));
                });
            }
        }
    }
}, { deep: true });

// Watcher para actualizar marcadores cuando cambien las sucursales
watch(() => props.sucursales, () => {
    addOtherSucursalesMarkers();
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
    <div class="map-picker" :class="{ 'fullscreen-mode': isFullscreen }">
        <label v-if="!isFullscreen" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
            {{ label }}
        </label>
        
        <!-- Buscador de ubicaciones -->
        <div v-if="!isFullscreen" class="mb-3 relative search-container">
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

        <div v-if="!isFullscreen" class="mb-2 flex gap-2 flex-wrap">
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

        <div class="relative">
         
            <!-- Indicador de sucursales -->
            <div v-if="sucursales && sucursales.length > 0 && !isFullscreen" class="absolute top-2 right-2 z-[1000] px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg shadow-lg text-xs border border-gray-300 dark:border-gray-600">
                <i class="pi pi-building mr-1 text-gray-500"></i>
                {{ sucursales.filter(s => s.latitude && s.longitude && s.id !== currentSucursalId).length }} sucursales
            </div>

            <div 
                ref="mapContainer" 
                :class="isFullscreen ? 'h-screen w-screen' : 'h-[60vh] w-full'"
                class="rounded-lg border-2 border-gray-300 dark:border-gray-600 mb-2"
            ></div>
        </div>

        <div v-if="currentLat && currentLng && !isFullscreen" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 p-3 rounded">
            <p class="font-semibold mb-1">Coordenadas seleccionadas:</p>
            <p><strong>Latitud:</strong> {{ currentLat}}</p>
            <p><strong>Longitud:</strong> {{ currentLng }}</p>
        </div>
        <div v-else-if="!isFullscreen" class="text-sm text-gray-500 dark:text-gray-400 italic">
            Haz clic en el mapa para seleccionar una ubicación
        </div>
    </div>
</template>

<style scoped>
.map-picker {
    width: 100%;
}

/* Modo pantalla completa */
.map-picker.fullscreen-mode {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    background: white;
    margin: 0;
    padding: 0;
}

/* Estilos para el mapa en modo oscuro */
:deep(.leaflet-container) {
    background: #f3f4f6;
}

/* Estilos para el marcador personalizado */
:deep(.custom-building-marker) {
    background: transparent;
    border: none;
}

/* Estilos para el popup con scroll interno */
:deep(.custom-popup .leaflet-popup-content-wrapper) {
    max-height: 300px;
    overflow-y: auto;
    padding: 0;
}

:deep(.custom-popup .leaflet-popup-content) {
    margin: 0;
    max-height: 300px;
    overflow-y: auto;
}

:deep(.custom-popup .popup-content) {
    font-family: inherit;
}

/* Scrollbar personalizado para el popup */
:deep(.custom-popup .leaflet-popup-content::-webkit-scrollbar) {
    width: 6px;
}

:deep(.custom-popup .leaflet-popup-content::-webkit-scrollbar-track) {
    background: #f1f1f1;
    border-radius: 3px;
}

:deep(.custom-popup .leaflet-popup-content::-webkit-scrollbar-thumb) {
    background: #888;
    border-radius: 3px;
}

:deep(.custom-popup .leaflet-popup-content::-webkit-scrollbar-thumb:hover) {
    background: #555;
}

/* Estilos para el popup de otras sucursales */
:deep(.other-sucursal-popup .leaflet-popup-content-wrapper) {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
}

/* Estilos para el botón de pantalla completa */
:deep(.leaflet-control-fullscreen) {
    background-color: white;
    border-radius: 4px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.4);
}

:deep(.leaflet-control-fullscreen a) {
    width: 30px;
    height: 30px;
    line-height: 30px;
    background-color: white;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
}

:deep(.leaflet-control-fullscreen a:hover) {
    background-color: #f4f4f4;
}

/* Ajustar el mapa en pantalla completa */
:deep(.leaflet-fullscreen-on) {
    width: 100% !important;
    height: 100% !important;
}
</style>
