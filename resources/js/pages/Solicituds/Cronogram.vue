<template>

    <Head title="Cronograma de Mantenimientos" />

    <AppLayout :breadcrumbs>
        <div class="flex h-full flex-1 flex-col gap-3 rounded-xl bg-white p-4 dark:bg-gray-800">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Cronograma</h1>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        Vista Gantt de solicitudes programadas
                    </p>
                </div>

                <div class="flex gap-1">
                    <button @click="zoomOut"
                        class="rounded px-2 py-1 text-xs font-medium hover:bg-gray-100 dark:hover:bg-gray-700"
                        title="Alejar">
                        <i class="pi pi-minus"></i>
                    </button>
                    <button @click="zoomIn"
                        class="rounded px-2 py-1 text-xs font-medium hover:bg-gray-100 dark:hover:bg-gray-700"
                        title="Acercar">
                        <i class="pi pi-plus"></i>
                    </button>
                    <button @click="resetZoom"
                        class="rounded bg-blue-600 px-2 py-1 text-xs font-medium text-white hover:bg-blue-700"
                        title="Restablecer">
                        <i class="pi pi-refresh"></i>
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="flex gap-2">
                <div class="flex-1">
                    <Input v-model="searchQuery" placeholder="Buscar..." class="w-full text-xs" />
                </div>
                <select v-model="filterEstado"
                    class="rounded border border-gray-300 px-2 py-1 text-xs dark:border-gray-600 dark:bg-gray-700">
                    <option value="">Todos</option>
                    <option value="Programada">Programada</option>
                    <option value="Nueva">Nueva</option>
                    <option value="Proceso">Proceso</option>
                    <option value="Finalizada">Finalizada</option>
                </select>
            </div>

            <!-- Gantt Chart -->
            <div class="flex-1 overflow-auto rounded border border-gray-200 dark:border-gray-700">
                <div class="min-w-max">
                    <!-- Timeline Header -->
                    <div
                        class="sticky top-0 z-10 flex border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                        <div class="w-64 border-r border-gray-200 px-3 py-2 dark:border-gray-700">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Solicitud</span>
                        </div>
                        <div class="flex flex-1">
                            <div v-for="date in timelineMonths" :key="date.key" :style="{ width: `${columnWidth}px` }"
                                class="border-r border-gray-200 px-1 py-1 text-center dark:border-gray-700">
                                <div class="text-[10px] font-semibold text-gray-700 dark:text-gray-300">
                                    {{ date.label }}
                                </div>
                                <div class="text-[9px] text-gray-500 dark:text-gray-400">
                                    {{ date.year }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gantt Rows -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700 h-[65vh]">
                        <div v-for="equipoGroup in groupedByEquipo" :key="equipoGroup.equipoId"
                            class="flex transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <!-- Equipo Info -->
                            <div class="w-64 border-r border-gray-200 px-3 py-2 dark:border-gray-700">
                                <div class="space-y-1">
                                    <p class="truncate text-xs font-semibold text-gray-900 dark:text-white">
                                        {{ equipoGroup.nombreEquipo }}
                                    </p>
                                    <p class="truncate text-[10px] text-gray-600 dark:text-gray-400">
                                        {{ equipoGroup.cliente }}
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[9px] font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                            {{ equipoGroup.solicitudes.length }} mantenimiento{{ equipoGroup.solicitudes.length !== 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="relative flex flex-1" style="height: 50px;">
                                <div v-for="date in timelineMonths" :key="date.key"
                                    :style="{ width: `${columnWidth}px` }"
                                    class="border-r border-gray-100 dark:border-gray-700"></div>

                                <!-- Gantt Bars - Múltiples por equipo -->
                                <template v-for="solicitud in equipoGroup.solicitudes" :key="solicitud.id">
                                    <div v-if="solicitud.fecha_programada" :style="getBarStyle(solicitud)"
                                        :class="getBarClass(solicitud)"
                                        class="absolute top-1/2 flex -translate-y-1/2 transform cursor-pointer items-center rounded px-2 py-1 text-[10px] font-medium text-white shadow transition-all hover:shadow-lg hover:z-10"
                                        @click="showSolicitudDetails(solicitud)" v-tooltip.top="getTooltipText(solicitud)">
                                        <span>{{ formatDateShort(solicitud.fecha_programada) }}</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="groupedByEquipo.length === 0" class="py-8 text-center">
                        <i class="pi pi-calendar-times mb-2 text-4xl text-gray-400"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">No hay solicitudes programadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalles -->
        <Dialog v-model:visible="showModal" modal :header="`Solicitud ${selectedSolicitud?.numero_orden || ''}`" :style="{ width: '40rem' }">
            <div v-if="selectedSolicitud" class="space-y-3 text-sm">
                <!-- Estado y Prioridad -->
                <div class="flex gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Estado:</span>
                        <Tag :value="selectedSolicitud.estado" :severity="getEstadoSeverity(selectedSolicitud.estado)" class="text-xs px-2 py-0.5" />
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Prioridad:</span>
                        <Tag :value="selectedSolicitud.prioridad" :severity="getPrioridadSeverity(selectedSolicitud.prioridad)" class="text-xs px-2 py-0.5" />
                    </div>
                </div>

                <!-- Cliente y Sucursal -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-xs text-gray-500">Cliente</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ selectedSolicitud.client?.enterprise_name || 'Sin cliente' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Sucursal</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ (selectedSolicitud as any).sucursal?.name || 'Sin sucursal' }}</p>
                    </div>
                </div>

                <!-- Equipo -->
                <div>
                    <span class="text-xs text-gray-500">Equipo</span>
                    <p class="font-medium text-gray-900 dark:text-white">{{ selectedSolicitud.equipo?.nombre_equipo || 'Sin equipo' }}</p>
                    <p class="text-xs text-gray-400">{{ (selectedSolicitud.equipo as any)?.tipo_equipo || '' }}</p>
                </div>

                <!-- Actividad y Fecha -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-xs text-gray-500">Actividad</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ selectedSolicitud.actividad }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Fecha Programada</span>
                        <div class="flex items-center gap-1.5 font-medium text-gray-900 dark:text-white">
                            <i class="pi pi-calendar text-[10px] text-blue-500"></i>
                            <span>{{ formatDate(selectedSolicitud.fecha_programada) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div v-if="(selectedSolicitud as any).detalles" class="pt-2">
                    <span class="text-xs text-gray-500">Detalles</span>
                    <p class="mt-1 rounded bg-gray-50 p-2 text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        {{ (selectedSolicitud as any).detalles }}
                    </p>
                </div>

                <!-- Información de Contacto -->
                <div v-if="(selectedSolicitud as any).quien_solicita || (selectedSolicitud as any).telefono || (selectedSolicitud as any).mail" class="border-t pt-3 dark:border-gray-700">
                    <div class="mb-2 text-xs font-semibold text-gray-700 dark:text-gray-300">Contacto</div>
                    <div class="grid grid-cols-3 gap-2">
                        <div v-if="(selectedSolicitud as any).quien_solicita">
                            <span class="text-[10px] text-gray-500">Solicitante</span>
                            <p class="text-xs font-medium text-gray-900 dark:text-white">{{ (selectedSolicitud as any).quien_solicita }}</p>
                        </div>
                        <div v-if="(selectedSolicitud as any).telefono">
                            <span class="text-[10px] text-gray-500">Teléfono</span>
                            <p class="text-xs font-medium text-gray-900 dark:text-white">{{ (selectedSolicitud as any).telefono }}</p>
                        </div>
                        <div v-if="(selectedSolicitud as any).mail">
                            <span class="text-[10px] text-gray-500">Email</span>
                            <p class="truncate text-xs font-medium text-gray-900 dark:text-white">{{ (selectedSolicitud as any).mail }}</p>
                        </div>
                    </div>
                </div>

                <!-- Técnico Asignado -->
                <div v-if="(selectedSolicitud as any).user" class="border-t pt-3 dark:border-gray-700">
                    <span class="text-xs text-gray-500">Técnico Asignado</span>
                    <div class="mt-1 flex items-center gap-1.5">
                        <i class="pi pi-user text-[10px] text-blue-500"></i>
                        <p class="font-medium text-gray-900 dark:text-white">{{ (selectedSolicitud as any).user?.name || 'No asignado' }}</p>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cerrar" severity="secondary" size="small" @click="closeModal" />
                    <!-- <Button label="Ver Completa" icon="pi pi-external-link" size="small" @click="goToSolicitud" /> -->
                </div>
            </template>
        </Dialog>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Input from '@/components/Input.vue';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import type { BreadcrumbItem } from '@/types';

interface Solicitud {
    id: string;
    numero_orden: string;
    fecha_programada: string;
    estado: string;
    actividad: string;
    prioridad: string;
    client?: {
        enterprise_name: string;
    };
    equipo?: {
        nombre_equipo: string;
    };
}

interface Props {
    solicituds: Solicitud[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Solicitudes', href: '/solicituds' },
    { title: 'Cronograma', href: '/solicituds-cronograma' },
];

// State
const searchQuery = ref('');
const filterEstado = ref('');
const columnWidth = ref(100);
const showModal = ref(false);
const selectedSolicitud = ref<Solicitud | null>(null);

// Timeline calculation
const timelineMonths = computed(() => {
    if (!props.solicituds || props.solicituds.length === 0) {
        // Mostrar próximos 12 meses por defecto
        const months = [];
        const today = new Date();
        for (let i = 0; i < 12; i++) {
            const date = new Date(today.getFullYear(), today.getMonth() + i, 1);
            months.push({
                key: `${date.getFullYear()}-${date.getMonth()}`,
                label: date.toLocaleDateString('es-ES', { month: 'short' }).toUpperCase(),
                year: date.getFullYear(),
                date: date,
            });
        }
        return months;
    }

    const dates = props.solicituds
        .filter(s => s.fecha_programada)
        .map(s => {
            // Si la fecha viene en formato YYYY-MM-DD sin hora, agregamos la hora para evitar problemas de zona horaria
            const fechaStr = s.fecha_programada.includes('T') ? s.fecha_programada : `${s.fecha_programada}T12:00:00`;
            return new Date(fechaStr);
        });

    const minDate = new Date(Math.min(...dates.map(d => d.getTime())));
    const maxDate = new Date(Math.max(...dates.map(d => d.getTime())));

    // Añadir 1 mes antes y después
    minDate.setMonth(minDate.getMonth() - 1);
    maxDate.setMonth(maxDate.getMonth() + 2);

    const months = [];
    const current = new Date(minDate.getFullYear(), minDate.getMonth(), 1);

    while (current <= maxDate) {
        months.push({
            key: `${current.getFullYear()}-${current.getMonth()}`,
            label: current.toLocaleDateString('es-ES', { month: 'short' }).toUpperCase(),
            year: current.getFullYear(),
            date: new Date(current),
        });
        current.setMonth(current.getMonth() + 1);
    }

    return months;
});

// Filtered solicitudes
const filteredSolicitudes = computed(() => {
    let filtered = props.solicituds || [];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(s =>
            s.client?.enterprise_name?.toLowerCase().includes(query) ||
            s.equipo?.nombre_equipo?.toLowerCase().includes(query) ||
            s.actividad?.toLowerCase().includes(query) ||
            s.numero_orden?.toLowerCase().includes(query)
        );
    }

    if (filterEstado.value) {
        filtered = filtered.filter(s => s.estado === filterEstado.value);
    }

    return filtered;
});

// Group solicitudes by equipo
const groupedByEquipo = computed(() => {
    const groups = new Map<string, {
        equipoId: string;
        nombreEquipo: string;
        cliente: string;
        solicitudes: Solicitud[];
    }>();

    filteredSolicitudes.value.forEach(solicitud => {
        const equipoId = (solicitud.equipo as any)?.id || 'sin-equipo';
        const nombreEquipo = solicitud.equipo?.nombre_equipo || 'Sin equipo';
        const cliente = solicitud.client?.enterprise_name || 'Sin cliente';

        if (!groups.has(equipoId)) {
            groups.set(equipoId, {
                equipoId,
                nombreEquipo,
                cliente,
                solicitudes: []
            });
        }

        groups.get(equipoId)!.solicitudes.push(solicitud);
    });

    // Convertir a array y ordenar por nombre de equipo
    return Array.from(groups.values()).sort((a, b) => 
        a.nombreEquipo.localeCompare(b.nombreEquipo)
    );
});

// Methods
const getBarStyle = (solicitud: Solicitud) => {
    // Si la fecha viene en formato YYYY-MM-DD sin hora, agregamos la hora para evitar problemas de zona horaria
    const fechaStr = solicitud.fecha_programada.includes('T') ? solicitud.fecha_programada : `${solicitud.fecha_programada}T12:00:00`;
    const fechaProgramada = new Date(fechaStr);
    const firstMonth = timelineMonths.value[0].date;

    // Calcular posición
    const monthsDiff = (fechaProgramada.getFullYear() - firstMonth.getFullYear()) * 12
        + (fechaProgramada.getMonth() - firstMonth.getMonth());

    const dayInMonth = fechaProgramada.getDate();
    const daysInMonth = new Date(fechaProgramada.getFullYear(), fechaProgramada.getMonth() + 1, 0).getDate();
    const positionInMonth = (dayInMonth / daysInMonth) * columnWidth.value;

    const left = monthsDiff * columnWidth.value + positionInMonth;

    return {
        left: `${left}px`,
        width: '60px', // Ancho más compacto para la barra
    };
};

const getBarClass = (solicitud: Solicitud) => {
    const classes: Record<string, string> = {
        'Programada': 'bg-blue-500 hover:bg-blue-600',
        'Nueva': 'bg-green-500 hover:bg-green-600',
        'Proceso': 'bg-yellow-500 hover:bg-yellow-600',
        'Finalizada': 'bg-gray-500 hover:bg-gray-600',
        'Anulada': 'bg-red-500 hover:bg-red-600',
    };
    return classes[solicitud.estado] || 'bg-purple-500 hover:bg-purple-600';
};

const getEstadoSeverity = (estado: string) => {
    const severities: Record<string, string> = {
        'Programada': 'info',
        'Nueva': 'success',
        'Proceso': 'warning',
        'Finalizada': 'secondary',
        'Anulada': 'danger',
    };
    return severities[estado] || 'info';
};

const formatDate = (date: string) => {
    // Si la fecha viene en formato YYYY-MM-DD sin hora, agregamos la hora para evitar problemas de zona horaria
    const dateStr = date.includes('T') ? date : `${date}T12:00:00`;
    return new Date(dateStr).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};

const formatDateShort = (date: string) => {
    // Si la fecha viene en formato YYYY-MM-DD sin hora, agregamos la hora para evitar problemas de zona horaria
    const dateStr = date.includes('T') ? date : `${date}T12:00:00`;
    return new Date(dateStr).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'short'
    });
};

const getTooltipText = (solicitud: Solicitud) => {
    return `${solicitud.numero_orden} - ${solicitud.client?.enterprise_name || 'Sin cliente'}\n${solicitud.equipo?.nombre_equipo || 'Sin equipo'}\n${formatDate(solicitud.fecha_programada)}`;
};

const showSolicitudDetails = (solicitud: Solicitud) => {
    selectedSolicitud.value = solicitud;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedSolicitud.value = null;
};

const goToSolicitud = () => {
    if (selectedSolicitud.value) {
        router.visit(`/solicituds/${selectedSolicitud.value.id}`);
    }
};

const getPrioridadSeverity = (prioridad: string) => {
    const severities: Record<string, string> = {
        'Normal': 'info',
        'Intermedio': 'warning',
        'Urgente': 'danger',
    };
    return severities[prioridad] || 'info';
};

const zoomIn = () => {
    columnWidth.value = Math.min(columnWidth.value + 20, 200);
};

const zoomOut = () => {
    columnWidth.value = Math.max(columnWidth.value - 20, 60);
};

const resetZoom = () => {
    columnWidth.value = 100;
};
</script>