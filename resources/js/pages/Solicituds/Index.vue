<script setup lang="ts">
import { ref, watch } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import Select from 'primevue/select';
import Input from '@/components/Input.vue';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import type { BreadcrumbItem, Solicitud, PaginatedResponse } from "@/types";
import { columns } from "./Columns";
import { Head, router, usePage } from '@inertiajs/vue3';
import Datatable from '@/components/Table/Datatable.vue';

import Modal from '@/components/Modal.vue';
import CancelSolicitudModal from '@/components/CancelSolicitudModal.vue';
import SolicitudDetailsDrawer from '@/components/SolicitudDetailsDrawer.vue';
import SolicitudService from '@/Services/SolicitudsService';
import Form from '@/pages/Solicituds/Form.vue';
import { watchDebounced } from '@vueuse/core';


const isAutorized = () => {
    return usePage().props.auth.user.role === 'Administrador';
}

interface Props {
    solicituds: PaginatedResponse<Solicitud>;
    filters?: {
        search?: string;
        per_page?: number;
        tipo?: string;
        estado?: string;
        mes?: string;
        anio?: string;
    };
}

const solicitud = ref<Solicitud | null>(null);
const solicitudToCancel = ref<Solicitud | null>(null);
const solicitudToView = ref<Solicitud | null>(null);
const selectedSolicitudes = ref<Solicitud[]>([]);

const props = defineProps<Props>();

const searchQuery = ref(props.filters?.search || "");
const perPage = ref(props.filters?.per_page || 10);
const tipoFilter = ref(props.filters?.tipo || "");
const estadoFilter = ref(props.filters?.estado || "");
const mesFilter = ref(props.filters?.mes || "");
const anioFilter = ref(props.filters?.anio || "");

// Opciones de filtros
const tipoOptions = [
    { label: 'Todos', value: '' },
    { label: 'Correctivo', value: 'Correctivo' },
    { label: 'Preventivo', value: 'Preventivo' }
];

const estadoOptions = [
    { label: 'Todos', value: '' },
    { label: 'Nueva', value: 'Nueva' },
    { label: 'Proceso', value: 'Proceso' },
    { label: 'Finalizada', value: 'Finalizada' },
];

const mesOptions = [
    { label: 'Todos', value: '' },
    { label: 'Enero', value: '1' },
    { label: 'Febrero', value: '2' },
    { label: 'Marzo', value: '3' },
    { label: 'Abril', value: '4' },
    { label: 'Mayo', value: '5' },
    { label: 'Junio', value: '6' },
    { label: 'Julio', value: '7' },
    { label: 'Agosto', value: '8' },
    { label: 'Septiembre', value: '9' },
    { label: 'Octubre', value: '10' },
    { label: 'Noviembre', value: '11' },
    { label: 'Diciembre', value: '12' }
];

const currentYear = new Date().getFullYear();
const anioOptions = [
    { label: 'Todos', value: '' },
    ...Array.from({ length: 5 }, (_, i) => ({
        label: (currentYear - i).toString(),
        value: (currentYear - i).toString()
    }))
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Solicitudes " + (props.filters?.tipo ? `(${props.filters.tipo})` : ''),
        href: "/solicituds",
    },
];

const showModal = ref(false);
const showCancelModal = ref(false);
const showDetailsDrawer = ref(false);

const add = () => {
    solicitud.value = null;
    showModal.value = true;
};

const solicitudService = new SolicitudService(solicitud.value);

const edit = (solicitudData: Solicitud) => {
    console.log(solicitudData);
    solicitud.value = solicitudData;
    console.log(solicitud.value);
    showModal.value = true;
};

const openCancelModal = (solicitudData: Solicitud) => {
    solicitudToCancel.value = solicitudData;
    selectedSolicitudes.value = [];
    showCancelModal.value = true;
};

const openBulkCancelModal = () => {
    solicitudToCancel.value = null;
    showCancelModal.value = true;
};

const viewDetails = (solicitudData: Solicitud) => {
    solicitudToView.value = solicitudData;
    showDetailsDrawer.value = true;
};

const handleCancelConfirm = (razon: string) => {
    if (solicitudToCancel.value) {
        // Cancelar una sola solicitud
        solicitudService.cancelar(solicitudToCancel.value.id, razon, () => {
            showCancelModal.value = false;
            solicitudToCancel.value = null;
        });
    } else if (selectedSolicitudes.value.length > 0) {
        // Cancelar múltiples solicitudes
        let completedCount = 0;
        const totalCount = selectedSolicitudes.value.length;

        selectedSolicitudes.value.forEach(solicitud => {
            solicitudService.cancelar(solicitud.id, razon, () => {
                completedCount++;
                if (completedCount === totalCount) {
                    showCancelModal.value = false;
                    selectedSolicitudes.value = [];
                    router.reload({ only: ['solicituds'] });
                }
            });
        });
    }
};

const toggleSelectAll = () => {
    const cancelableSolicitudes = props.solicituds.data.filter((s: Solicitud) => s.estado !== 'Anulada');
    if (selectedSolicitudes.value.length === cancelableSolicitudes.length) {
        selectedSolicitudes.value = [];
    } else {
        selectedSolicitudes.value = cancelableSolicitudes;
    }
};

const toggleSelect = (solicitudData: Solicitud) => {
    const index = selectedSolicitudes.value.findIndex(s => s.id === solicitudData.id);
    if (index > -1) {
        selectedSolicitudes.value.splice(index, 1);
    } else {
        selectedSolicitudes.value.push(solicitudData);
    }
};

const isSelected = (solicitudData: Solicitud) => {
    return selectedSolicitudes.value.some(s => s.id === solicitudData.id);
};

const onPageChange = (event: any) => {
    perPage.value = event.rows;
    performSearch(event.page + 1);
};

const performSearch = (page = 1) => {
    router.get('/solicituds', {
        page: page,
        per_page: perPage.value,
        search: searchQuery.value || undefined,
        tipo: tipoFilter.value || undefined,
        estado: estadoFilter.value || undefined,
        mes: mesFilter.value || undefined,
        anio: anioFilter.value || undefined
    }, {
        preserveState: true,
        preserveScroll: page !== 1,
        only: ['solicituds']
    });
};

// Búsqueda con debounce para evitar muchas peticiones
watchDebounced(
    searchQuery,
    () => {
        performSearch(1); // Volver a página 1 cuando se busca
    },
    { debounce: 500 }
);

// Filtros inmediatos (sin debounce)
watch(tipoFilter, () => {
    performSearch(1);
});

watch(estadoFilter, () => {
    performSearch(1);
});

watch(mesFilter, () => {
    performSearch(1);
});

watch(anioFilter, () => {
    performSearch(1);
});

const crearReporte = (solicitudData: Solicitud) => {
    router.get(`/informe/${solicitudData.id}`, {}, {
    });
};

const generateReport = (solicitudData: Solicitud) => {
    window.location.href = `/informe/${solicitudData.id}/pdf`;
};

const canGenerateInforme = (solicitudData: Solicitud) => {
    if ( usePage().props.auth.user.role === 'Cliente') {
        return false;
    }
    if (!solicitudData.fecha_informe ) {
        return true;
    }
    
    if(usePage().props.auth.user.role === 'Administrador'){
        return true;
    }

    return solicitudData.fecha_informe
        && (() => {
            const ts = Date.parse(solicitudData.fecha_informe as unknown as string);
            if (isNaN(ts)) {
                return false;
            }
            const hoursSince = (Date.now() - ts) / (1000 * 60 * 60);
            return hoursSince <= 72;
        })();
}

const exportToExcel = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        tipo: tipoFilter.value || '',
        estado: estadoFilter.value || '',
        mes: mesFilter.value || '',
        anio: anioFilter.value || ''
    });
    window.location.href = `/solicituds-export-excel?${params.toString()}`;
};

const getRoutePdf = (solicitudData: Solicitud) => {
    if(solicitudData.pdf_path){
        return '/public/pdf/' + solicitudData.pdf_path;
    }
    return `/informe/${solicitudData.id}/pdf`;
}


</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Solicitudes" />

        <div>
            <!-- Barra de búsqueda y filtros -->
            <div class="mb-4 space-y-3 flex items-center justify-between">
                <!-- Primera fila: Búsqueda y acciones principales -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <div class="flex-1">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <Input v-model="searchQuery" placeholder="Buscar solicitudes..." class="w-full" />
                        </IconField>
                    </div>
                    <Button v-if="isAutorized() && selectedSolicitudes.length > 0"
                        :label="`Cancelar (${selectedSolicitudes.length})`" icon="pi pi-ban" severity="danger" size="small"
                        @click="openBulkCancelModal" />
                    <Button label="Agregar Solicitud"
                        v-if="(isAutorized() || ($page.props.auth.user.role === 'Cliente' && props.filters?.tipo === 'Mantenimiento Correctivo')) && props.filters?.tipo"
                        icon="pi pi-plus" size="small" @click="add" />
                    <Button label="Exportar a Excel" size="small" icon="pi pi-file-excel" severity="success" @click="exportToExcel" />
                </div>

                <!-- Segunda fila: Filtros -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <Select v-model="estadoFilter" :options="estadoOptions" optionLabel="label" optionValue="value"
                        placeholder="Estado" class="w-full sm:w-40" />
                    <Select v-model="mesFilter" :options="mesOptions" optionLabel="label" optionValue="value"
                        placeholder="Mes" class="w-full sm:w-40" />
                    <Select v-model="anioFilter" :options="anioOptions" optionLabel="label" optionValue="value"
                        placeholder="Año" class="w-full sm:w-32" />
                </div>
            </div>

            <!-- Vista de Tabla para pantallas grandes -->
            <div class="hidden md:block">
                <Datatable :columns="columns" :data="solicituds.data" :noShowHeader="true">
                    <template #addButton>
                        <!-- Removido porque ahora está arriba -->
                    </template>
                    <template #detalles="{ data }">
                        <p v-html="data.detalles"></p>
                    </template>
                    <template #select="{ data }">
                        <div class="flex items-center justify-center" v-if="isAutorized() && data.estado !== 'Anulada'">
                            <input type="checkbox" :checked="isSelected(data)" @change="toggleSelect(data)"
                                class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                        </div>
                    </template>
                    <template #actions="{ data }">
                        <div class="flex justify-end">
                            <Button icon="pi pi-eye" size="small" severity="secondary" text
                                v-tooltip.left="`Ver Detalles`" @click="viewDetails(data)" />
                            
                            <a :href="getRoutePdf(data)" target="_blank" v-if="data.informe_generado" v-tooltip.top="`Ver Informe`">
                                <Button icon="pi pi-file-pdf" size="small" severity="danger" text/>
                            </a>
                            <Button icon="pi pi-file" size="small" severity="info" text
                                v-tooltip.left="`Generar Informe`" @click="crearReporte(data)"
                                v-if="canGenerateInforme(data)" />
                            <Button icon="pi pi-pencil" size="small" v-if="isAutorized() && data.estado != 'Anulada'"
                                severity="warn" text v-tooltip.left="`Editar`" @click="edit(data)" />
                            <Button icon="pi pi-ban" size="small" severity="danger" text
                                v-if="isAutorized() && data.estado != 'Anulada'" v-tooltip.left="`Cancelar`"
                                @click="openCancelModal(data)" />
                        </div>
                    </template>
                </Datatable>
            </div>

            <!-- Vista de Tarjetas para móviles -->
            <div class="md:hidden space-y-3">
                <div v-for="solicitudItem in solicituds.data" :key="solicitudItem.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header compacto de la tarjeta -->
                    <div
                        class="px-3 py-2 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <input v-if="isAutorized() && solicitudItem.estado !== 'Anulada'" type="checkbox"
                                :checked="isSelected(solicitudItem)" @change="toggleSelect(solicitudItem)"
                                class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                            <i class="pi pi-file text-gray-500 dark:text-gray-400 text-xs"></i>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                #{{ solicitudItem.numero_orden }}
                            </span>
                        </div>
                        <span :class="{
                            'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200': solicitudItem.prioridad === 'Normal',
                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200': solicitudItem.prioridad === 'Intermedio',
                            'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200': solicitudItem.prioridad === 'Urgente'
                        }" class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide">
                            {{ solicitudItem.prioridad }}
                        </span>
                    </div>

                    <!-- Contenido compacto en 2 columnas -->
                    <div class="px-3 py-2.5 space-y-2">
                        <!-- Fila 1: Cliente y Sucursal -->
                        <div class="grid grid-cols-2 gap-2">
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-0.5 flex items-center gap-1">
                                    <i class="pi pi-building text-[9px]"></i>
                                    <span>Cliente</span>
                                </p>
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                    {{ solicitudItem.client?.contact_name || 'N/A' }}
                                </p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-0.5 flex items-center gap-1">
                                    <i class="pi pi-map-marker text-[9px]"></i>
                                    <span>Sucursal</span>
                                </p>
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                    {{ solicitudItem.sucursal?.name || 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Fila 2: Equipo y Técnico -->
                        <div class="grid grid-cols-2 gap-2">
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-0.5 flex items-center gap-1">
                                    <i class="pi pi-cog text-[9px]"></i>
                                    <span>Direccion</span>
                                </p>
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                    {{ solicitudItem.sucursal?.address || 'N/A' }}
                                </p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-0.5 flex items-center gap-1">
                                    <i class="pi pi-user text-[9px]"></i>
                                    <span>Contcto</span>
                                </p>
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                    {{ solicitudItem.sucursal?.contact_name || 'Sin asignar' }}
                                </p>
                            </div>
                        </div>

                        <!-- Fila 3: Fechas -->
                        <div class="grid grid-cols-2 gap-2 pt-1.5 border-t border-gray-100 dark:border-gray-700">
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-0.5 flex items-center gap-1">
                                    <i class="pi pi-phone text-[9px]"></i>
                                    <span>Telefono</span>
                                </p>
                                <a :href="`tel:${solicitudItem.telefono}`"
                                    class="text-[11px] font-medium text-gray-900 dark:text-white">
                                    {{ solicitudItem.telefono || 'N/A' }}
                                </a>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-0.5 flex items-center gap-1">
                                    <i class="pi pi-clock text-[9px]"></i>
                                    <span>Programada</span>
                                </p>
                                <p class="text-[11px] font-medium text-gray-900 dark:text-white">
                                    {{ solicitudItem.fecha_programada
                                        ? new Date(solicitudItem.fecha_programada).toLocaleDateString('es-ES', {
                                            day: '2-digit',
                                            month: 'short'
                                        })
                                        : 'Sin prog.'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-3 py-2 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-1.5">
                        <div class="flex items-center gap-1.5">
                            <!-- Mostrar opciones si hay coordenadas -->
                            <template v-if="solicitudItem.sucursal?.latitude && solicitudItem.sucursal?.longitude">
                                <a :href="`https://www.google.com/maps/dir/?api=1&destination=${solicitudItem.sucursal.latitude},${solicitudItem.sucursal.longitude}`"
                                    target="_blank" rel="noopener">
                                    <Button text icon="pi pi-map" size="small" v-tooltip.top="'Abrir en Google Maps'"
                                        class="!p-1.5" />
                                </a>

                                <a :href="`https://waze.com/ul?ll=${solicitudItem.sucursal.latitude},${solicitudItem.sucursal.longitude}&navigate=yes`"
                                    target="_blank" rel="noopener">
                                    <img src="/svg/waze.svg" class="size-4" alt="">
                                </a>
                            </template>

                        </div>
                        <!-- ver detalles  -->
                        <Button icon="pi pi-eye" size="small" severity="secondary" text v-tooltip.top="'Ver Detalles'"
                            @click="viewDetails(solicitudItem)" class="!p-1.5" />
                        <a :href="getRoutePdf(solicitudItem)" target="_blank" v-if="solicitudItem.informe_generado" v-tooltip.top="`Ver Informe`"
                            class="!p-1.5"  />

                        <Button v-if="canGenerateInforme(solicitudItem)"
                            icon="pi pi-file" size="small" severity="info" text v-tooltip.top="'Generar Informe'"
                            @click="crearReporte(solicitudItem)" class="!p-1.5" />

                        <Button icon="pi pi-pencil" size="small" severity="warn" text v-tooltip.top="'Editar'"
                            @click="edit(solicitudItem)" class="!p-1.5"
                            v-if="isAutorized() && solicitudItem.estado != 'Anulada'" />
                        <Button icon="pi pi-ban" size="small" severity="danger"
                            v-if="isAutorized() && solicitudItem.estado != 'Anulada'" text v-tooltip.top="'Cancelar'"
                            @click="openCancelModal(solicitudItem)" class="!p-1.5" />
                    </div>
                </div>

                <!-- Mensaje cuando no hay datos -->
                <div v-if="solicituds.data.length === 0"
                    class="text-center py-8 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <i class="pi pi-inbox text-3xl text-gray-400 dark:text-gray-600 mb-2"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400">No se encontraron solicitudes</p>
                </div>
            </div>

            <div
                class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    <span>Mostrando</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ (solicituds.current_page - 1) * solicituds.per_page + 1 }}
                    </span>
                    <span>a</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ Math.min(solicituds.current_page * solicituds.per_page, solicituds.total) }}
                    </span>
                    <span>de</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ solicituds.total }}</span>
                    <span class="hidden sm:inline">solicitudes</span>
                </div>

                <Paginator :rows="solicituds.per_page" :totalRecords="solicituds.total"
                    :first="(solicituds.current_page - 1) * solicituds.per_page"
                    :rowsPerPageOptions="[10, 15, 20, 30, 50]" @page="onPageChange"
                    template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    class="paginator-custom" />
            </div>
        </div>

        <Modal v-model="showModal"
            :title="(solicitud?.id ? 'Editar Solicitud ' : 'Agregar Solicitud ') + (filters?.tipo ?? '')"
            :maximizable="true" width="80vw">
            <Form :solicitud="solicitud" @close="showModal = false" :tipo="filters?.tipo" />
        </Modal>

        <CancelSolicitudModal v-model="showCancelModal"
            :solicitud-numero="solicitudToCancel?.numero_orden || (selectedSolicitudes.length > 0 ? `${selectedSolicitudes.length} solicitudes` : undefined)"
            @confirm="handleCancelConfirm" />

        <SolicitudDetailsDrawer v-model="showDetailsDrawer" :solicitud="solicitudToView" />
    </AppLayout>

</template>

<style scoped>
:deep(.paginator-custom) {
    background: transparent;
    border: none;
    padding: 0;
}

:deep(.paginator-custom .p-paginator-pages),
:deep(.paginator-custom .p-paginator-first),
:deep(.paginator-custom .p-paginator-prev),
:deep(.paginator-custom .p-paginator-next),
:deep(.paginator-custom .p-paginator-last) {
    display: inline-flex;
    gap: 0.25rem;
}

:deep(.paginator-custom .p-paginator-page),
:deep(.paginator-custom .p-paginator-first),
:deep(.paginator-custom .p-paginator-prev),
:deep(.paginator-custom .p-paginator-next),
:deep(.paginator-custom .p-paginator-last) {
    min-width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
    font-weight: 500;
}

/* Móvil: botones más pequeños */
@media (max-width: 640px) {

    :deep(.paginator-custom .p-paginator-page),
    :deep(.paginator-custom .p-paginator-first),
    :deep(.paginator-custom .p-paginator-prev),
    :deep(.paginator-custom .p-paginator-next),
    :deep(.paginator-custom .p-paginator-last) {
        min-width: 2rem;
        height: 2rem;
        font-size: 0.875rem;
    }

    :deep(.paginator-custom .p-dropdown) {
        font-size: 0.875rem;
    }

    /* Ocultar algunos números de página en móvil para ahorrar espacio */
    :deep(.paginator-custom .p-paginator-pages .p-paginator-page) {
        display: none;
    }

    :deep(.paginator-custom .p-paginator-pages .p-paginator-page.p-highlight),
    :deep(.paginator-custom .p-paginator-pages .p-paginator-page:first-child),
    :deep(.paginator-custom .p-paginator-pages .p-paginator-page:last-child) {
        display: inline-flex;
    }
}

:deep(.paginator-custom .p-paginator-page:not(.p-disabled):hover),
:deep(.paginator-custom .p-paginator-first:not(.p-disabled):hover),
:deep(.paginator-custom .p-paginator-prev:not(.p-disabled):hover),
:deep(.paginator-custom .p-paginator-next:not(.p-disabled):hover),
:deep(.paginator-custom .p-paginator-last:not(.p-disabled):hover) {
    background-color: rgb(243 244 246 / 1);
    transform: translateY(-1px);
}

:deep(.dark .paginator-custom .p-paginator-page:not(.p-disabled):hover),
:deep(.dark .paginator-custom .p-paginator-first:not(.p-disabled):hover),
:deep(.dark .paginator-custom .p-paginator-prev:not(.p-disabled):hover),
:deep(.dark .paginator-custom .p-paginator-next:not(.p-disabled):hover),
:deep(.dark .paginator-custom .p-paginator-last:not(.p-disabled):hover) {
    background-color: rgb(55 65 81 / 1);
}

:deep(.paginator-custom .p-paginator-page.p-highlight) {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 6px -1px rgb(220 38 38 / 0.3);
}

:deep(.paginator-custom .p-dropdown) {
    border-radius: 0.5rem;
    border: 1px solid rgb(229 231 235 / 1);
    transition: all 0.2s;
}

:deep(.dark .paginator-custom .p-dropdown) {
    border-color: rgb(55 65 81 / 1);
}

:deep(.paginator-custom .p-dropdown:hover) {
    border-color: #dc2626;
}

:deep(.paginator-custom .p-paginator-rpp-options) {
    margin-left: 1rem;
}

@media (max-width: 640px) {
    :deep(.paginator-custom .p-paginator-rpp-options) {
        margin-left: 0.5rem;
    }
}
</style>
