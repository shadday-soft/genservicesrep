<script setup lang="ts">
import { computed } from 'vue';
import Drawer from 'primevue/drawer';
import Button from 'primevue/button';
import type { Solicitud } from '@/types';

interface Props {
    modelValue: boolean;
    solicitud: Solicitud | null;
}

interface Emits {
    (e: 'update:modelValue', value: boolean): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const visible = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const formatDate = (date: string | null) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getEstadoBadgeClass = (estado: string) => {
    const classes = {
        'Nueva': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'Proceso': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'Finalizada': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'Cancelada': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
    };
    return classes[estado as keyof typeof classes] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const getPrioridadBadgeClass = (prioridad: string) => {
    const classes = {
        'Normal': 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200',
        'Intermedio': 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200',
        'Urgente': 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200'
    };
    return classes[prioridad as keyof typeof classes] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};
</script>

<template>
    <Drawer v-model:visible="visible" position="right" class="!w-full md:!w-[600px]" header="Detalles de la Solicitud">
        <template #header>
            <div class="flex items-center gap-3">
                <i class="pi pi-file-edit text-2xl text-primary-600 dark:text-primary-400"></i>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Detalles de la Solicitud
                    </h2>
                    <p v-if="solicitud" class="text-sm text-gray-500 dark:text-gray-400">
                        #{{ solicitud.numero_orden }}
                    </p>
                </div>
            </div>
        </template>

        <div v-if="solicitud" class="space-y-6">
            <!-- Estado y Prioridad -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</span>
                    <span :class="getEstadoBadgeClass(solicitud.estado)" 
                          class="px-3 py-1 rounded-full text-xs font-semibold">
                        {{ solicitud.estado }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Prioridad</span>
                    <span :class="getPrioridadBadgeClass(solicitud.prioridad)" 
                          class="px-3 py-1 rounded-full text-xs font-semibold">
                        {{ solicitud.prioridad }}
                    </span>
                </div>
                <div v-if="solicitud.orden_trabajo" class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Orden de Trabajo</span>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full text-xs font-semibold">
                        {{ solicitud.orden_trabajo }}
                    </span>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="pi pi-building text-primary-600 dark:text-primary-400"></i>
                    Información del Cliente
                </h3>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nombre</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ solicitud.client?.contact_name || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">NIT</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ solicitud.client?.nit || 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Email</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.client?.email || 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Teléfono</p>
                        <a v-if="solicitud.telefono" 
                           :href="`tel:${solicitud.telefono}`"
                           class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                            {{ solicitud.telefono }}
                        </a>
                        <p v-else class="text-sm font-medium text-gray-900 dark:text-white">N/A</p>
                    </div>
                </div>
            </div>

            <!-- Información de la Sucursal -->
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="pi pi-map-marker text-primary-600 dark:text-primary-400"></i>
                    Información de la Sucursal
                </h3>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nombre</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.sucursal?.name || 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Dirección</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.sucursal?.address || 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Contacto</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.sucursal?.contact_name || 'N/A' }}
                        </p>
                    </div>
                    <div v-if="solicitud.sucursal?.latitude && solicitud.sucursal?.longitude" class="flex gap-2">
                        <a :href="`https://www.google.com/maps/dir/?api=1&destination=${solicitud.sucursal.latitude},${solicitud.sucursal.longitude}`"
                           target="_blank" rel="noopener" class="flex-1">
                            <Button label="Abrir en Google Maps" icon="pi pi-map" size="small" outlined class="w-full" />
                        </a>
                        <a :href="`https://waze.com/ul?ll=${solicitud.sucursal.latitude},${solicitud.sucursal.longitude}&navigate=yes`"
                           target="_blank" rel="noopener">
                            <Button size="small" outlined class="!px-3">
                                <img src="/svg/waze.svg" class="size-4" alt="Waze">
                            </Button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información de Servicio -->
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="pi pi-cog text-primary-600 dark:text-primary-400"></i>
                    Información del Servicio
                </h3>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Equipo</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.equipo?.nombre_equipo || 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tipo de Equipo</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.equipo?.tipo_equipo || 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Quien Solicita</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ solicitud.quien_solicita || 'Sin asignar' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Detalles del Servicio</p>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap" v-html="solicitud.detalles || 'Sin detalles'   ">
                          
                        </p>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="pi pi-calendar text-primary-600 dark:text-primary-400"></i>
                    Fechas Importantes
                </h3>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fecha de Creación</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ formatDate(solicitud.created_at) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fecha Programada</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ solicitud.fecha_programada ? formatDate(solicitud.fecha_programada) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div v-if="solicitud.fecha_mantenimiento">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fecha de Mantenimiento</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ formatDate(solicitud.fecha_mantenimiento) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Razón de cancelación si aplica -->
            <div v-if="solicitud.razon_cancelacion" class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="pi pi-exclamation-circle text-red-600 dark:text-red-400"></i>
                    Razón de Cancelación
                </h3>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm text-red-900 dark:text-red-200 whitespace-pre-wrap">
                        {{ solicitud.razon_cancelacion }}
                    </p>
                </div>
            </div>
        </div>

        <div v-else class="flex items-center justify-center h-64">
            <p class="text-gray-500 dark:text-gray-400">No hay datos de solicitud disponibles</p>
        </div>

        <template #footer>
            <div class="flex justify-end">
                <Button label="Cerrar" icon="pi pi-times" @click="visible = false" outlined />
            </div>
        </template>
    </Drawer>
</template>

<style scoped>
:deep(.p-drawer-header) {
    padding: 1.5rem;
    border-bottom: 1px solid rgb(229 231 235 / 1);
}

:deep(.dark .p-drawer-header) {
    border-bottom-color: rgb(55 65 81 / 1);
}

:deep(.p-drawer-content) {
    padding: 1.5rem;
}

:deep(.p-drawer-footer) {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgb(229 231 235 / 1);
}

:deep(.dark .p-drawer-footer) {
    border-top-color: rgb(55 65 81 / 1);
}
</style>
