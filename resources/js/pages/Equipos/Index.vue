<script setup lang="ts">
import { ref, computed } from 'vue';
import { getSuccessMessage, getErrorMessage } from '@/composables/Toast';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import Input from '@/components/Input.vue';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import type { BreadcrumbItem, PaginatedResponse } from "@/types";
import { columns } from "./Columns";
import { Head, router } from '@inertiajs/vue3';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';
import EquipoService from '@/Services/EquiposService';
import axios from 'axios';
import { watchDebounced } from '@vueuse/core';

interface Props {
    equipos: PaginatedResponse<import('@/types').Equipo>;
    filters?: {
        search?: string;
        per_page?: number;
    };
}

const equipo = ref<import('@/types').Equipo | null>(null);

const props = defineProps<Props>();

const searchQuery = ref(props.filters?.search || '');
const perPage = ref(props.filters?.per_page || 15);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Equipos",
        href: "/equipos",
    },
];

const showModal = ref(false);

const add = () => {
    equipo.value = null;
    showModal.value = true;
};

const equipoService = new EquipoService(equipo.value);

const edit = (equipoData: any) => {
    equipo.value = equipoData;
    showModal.value = true;
};

// Renovar contrato
const renewModalVisible = ref(false);
const renewEquipo = ref<any | null>(null);
const renewService = ref<any | null>(null);
const renewFecha = ref('');
const renewPeriodicidad = ref('Mensual');

const openRenewModal = (equipoData: any) => {
    renewEquipo.value = equipoData;
    renewService.value = new EquipoService(equipoData);
    // valores por defecto
    renewPeriodicidad.value = equipoData.periodicidad || 'Mensual';
    // si tiene fecha_primer_mantenimiento, usarla
    renewFecha.value = equipoData.fecha_primer_mantenimiento ? new Date(equipoData.fecha_primer_mantenimiento).toISOString().slice(0,10) : new Date().toISOString().slice(0,10);
    renewModalVisible.value = true;
};

const closeRenew = () => {
    renewModalVisible.value = false;
    renewEquipo.value = null;
    renewService.value = null;
};

const submitRenew = async () => {
    if (!renewService.value || !renewEquipo.value) return;

    // ajustar el servicio con los valores seleccionados
    renewService.value.form.periodicidad = renewPeriodicidad.value;
    renewService.value.form.fecha_primer_mantenimiento = new Date(renewFecha.value);

    const fechas = renewService.value.calcularProximasMantenimientos();

    try {
        const payload = fechas.map((d: Date) => d.toISOString().split('T')[0]);
        await axios.post(`/equipos/${renewEquipo.value.id}/renew`, { fechas: payload });
        // refrescar lista
        performSearch(1);
        closeRenew();
        getSuccessMessage('Renovación procesada correctamente.');
    } catch (error) {
        // eslint-disable-next-line no-console
        console.error('Error renovando contrato:', error);
        getErrorMessage('Error al procesar la renovación.');
    }
};

// Preview de fechas calculadas para mostrar en el modal (detallado)
const previewFechas = computed(() => {
    if (!renewService.value) return [] as string[];

    // sincronizar valores en el servicio (necesario para la función de cálculo)
    renewService.value.form.periodicidad = renewPeriodicidad.value;
    renewService.value.form.fecha_primer_mantenimiento = new Date(renewFecha.value);

    const fechasAjustadas: Date[] = renewService.value.calcularProximasMantenimientos() || [];

    // Reconstruir las fechas "crudas" sin ajuste para detectar las evitadas
    const rawDates: Date[] = [];
    const inicio = new Date(renewFecha.value);
    let incremento = 0;
    switch (renewPeriodicidad.value) {
        case 'Semanal': incremento = 7; break;
        case 'Mensual': incremento = 31; break;
        case 'Trimestral': incremento = Math.round(365 / 4) + 1; break;
        case 'Semestral': incremento = Math.round(365 / 2) + 1; break;
        case 'Anual': incremento = 365; break;
        default: incremento = 0; break;
    }

    if (incremento > 0) {
        let current = new Date(inicio);
        const limit = new Date(inicio);
        limit.setFullYear(limit.getFullYear() + 1);
        rawDates.push(new Date(current));
        while (true) {
            current = new Date(current);
            current.setDate(current.getDate() + incremento);
            if (current >= limit) break;
            rawDates.push(new Date(current));
        }
    }

    // Mapear a objetos con información de si fue evitada (no hábil en raw)
    const detailed = fechasAjustadas.map((ajustada, idx) => {
        const raw = rawDates[idx] || ajustada;
        const avoided = !renewService.value.esDiaHabil(raw);
        return {
            fecha: new Date(ajustada),
            fechaFormatted: new Date(ajustada).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' }),
            rawFormatted: new Date(raw).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' }),
            avoided,
        };
    });

    return detailed;
});

// Resumen por mes: { 'Nov 2025': count }
const monthlyCounts = computed(() => {
    const map = new Map<string, number>();
    previewFechas.value.forEach((item: any) => {
        const d = item.fecha;
        const key = d.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        map.set(key, (map.get(key) || 0) + 1);
    });
    return Array.from(map.entries()).map(([k, v]) => ({ month: k, count: v }));
});

const onPageChange = (event: any) => {
    perPage.value = event.rows;
    performSearch(event.page + 1);
};

const performSearch = (page = 1) => {
    router.get('/equipos', {
        page: page,
        per_page: perPage.value,
        search: searchQuery.value || undefined
    }, {
        preserveState: true,
        preserveScroll: page !== 1,
        only: ['equipos']
    });
};

// Búsqueda con debounce
watchDebounced(
    searchQuery,
    () => {
        performSearch(1);
    },
    { debounce: 500 }
);

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Equipos" />

        <div>
            <!-- Barra de búsqueda global -->
            <div class="mb-4 flex items-center gap-3">
                <div class="flex-1">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <Input 
                            v-model="searchQuery" 
                            placeholder="Buscar equipos..." 
                            class="w-full"
                        />
                    </IconField>
                </div>
                <Button label="Agregar Equipo" icon="pi pi-plus" size="small" @click="add" />
            </div>

            <Datatable :columns="columns" :data="equipos.data" :noShowHeader="true">
                <template #addButton>
                    <!-- Removido porque ahora está arriba -->
                </template>
                <template #actions="{ data }">

                    <Button icon="pi pi-sync" size="small" severity="info" text v-tooltip.left="`Renovar Contrato`" @click="openRenewModal(data)" />
                    <Button icon="pi pi-pencil" size="small" severity="warn" text v-tooltip.left="`Editar`" @click="edit(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="equipoService.delete(data.id)" />
                </template>
            </Datatable>

            <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span>Mostrando</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ (equipos.current_page - 1) * equipos.per_page + 1 }}
                    </span>
                    <span>a</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ Math.min(equipos.current_page * equipos.per_page, equipos.total) }}
                    </span>
                    <span>de</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ equipos.total }}</span>
                    <span>equipos</span>
                </div>

                <Paginator 
                    :rows="equipos.per_page"
                    :totalRecords="equipos.total"
                    :first="(equipos.current_page - 1) * equipos.per_page"
                    :rowsPerPageOptions="[10, 15, 20, 30, 50]"
                    @page="onPageChange"
                    template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    class="paginator-custom"
                />
            </div>
        </div>

        <Modal v-model="showModal" :title="equipo?.id ? 'Editar Equipo' : 'Agregar Equipo'" width="80vw" :maximizable="true">
            <Form :equipo="equipo" @close="showModal = false" />
        </Modal>

        <!-- Modal de Renovación de Contrato -->
        <Modal v-model="renewModalVisible" title="Renovar Contrato" width="30rem">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-600">Fecha inicio</label>
                    <input type="date" v-model="renewFecha" class="w-full rounded border px-2 py-1 text-sm" />
                </div>

                <div>
                    <label class="block text-xs text-gray-600">Periodicidad</label>
                    <select v-model="renewPeriodicidad" class="w-full rounded border px-2 py-1 text-sm">
                        <option>Semanal</option>
                        <option>Mensual</option>
                        <option>Trimestral</option>
                        <option>Semestral</option>
                        <option>Anual</option>
                    </select>
                </div>

                <div class="text-xs text-gray-600">Se generarán solicitudes de mantenimiento para el equipo seleccionado según la periodicidad y la fecha inicial.</div>

                <div class="pt-2">
                    <div class="text-xs font-semibold text-gray-700 mb-1">Fechas previstas</div>
                    <div v-if="previewFechas.length === 0" class="text-xs text-gray-500">No hay fechas calculadas</div>

                    <div v-else>
                        <div class="mb-2 text-xs text-gray-600">Resumen por mes:</div>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span v-for="item in monthlyCounts" :key="item.month" class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ item.month }}: {{ item.count }}</span>
                        </div>

                        <ul class="space-y-1">
                            <li v-for="(f, idx) in previewFechas" :key="idx" class="text-xs text-gray-800 dark:text-gray-200 flex items-center justify-between">
                                <div>
                                    • <span class="font-medium">{{ (f as any).fechaFormatted }}</span>
                                    <span v-if="(f as any).avoided" class="ml-2 text-[11px] text-yellow-600">(ajustada desde {{ (f as any).rawFormatted }})</span>
                                </div>
                                <div class="text-[11px] text-gray-500">{{ (f as any).avoided ? 'Ajustada' : 'OK' }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cancelar" severity="secondary" size="small" @click="closeRenew" />
                    <Button :disabled="!renewPeriodicidad || previewFechas.length === 0" label="Generar" icon="pi pi-check" size="small" @click="submitRenew" />
                </div>
            </template>
        </Modal>
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
</style>