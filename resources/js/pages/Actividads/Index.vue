<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import type { BreadcrumbItem, PaginatedResponse } from "@/types";
import type { Actividad } from '@/types';
import { columns } from "./Columns";
import { Head, router } from '@inertiajs/vue3';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';
import ActividadService from '@/Services/ActividadsService';

interface Props {
    actividads: PaginatedResponse<Actividad>;
    filters?: {
        search?: string;
        per_page?: number;
    };
}

const actividad = ref<Actividad | null>(null);

const props = defineProps<Props>();

const perPage = ref(props.filters?.per_page || 15);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Actividades",
        href: "/actividads",
    },
];

const showModal = ref(false);

const add = () => {
    actividad.value = null;
    showModal.value = true;
};

const actividadService = new ActividadService(actividad.value);

const edit = (actividadData: Actividad) => {
    actividad.value = actividadData;
    showModal.value = true;
};

const onPageChange = (event: any) => {
    perPage.value = event.rows;
    router.get('/actividads', {
        page: event.page + 1,
        per_page: perPage.value
    }, {
        preserveState: true,
        preserveScroll: event.page !== 0,
        only: ['actividads']
    });
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Actividades" />

        <div>
            <Datatable :columns="columns" :data="actividads.data">
                <template #addButton>
                    <div class="flex gap-x-2">
                        <Button label="Agregar Actividad" icon="pi pi-plus" size="small" @click="add" />
                    </div>
                </template>
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text v-tooltip.left="`Editar`" @click="edit(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="actividadService.delete(data.id)" />
                </template>
            </Datatable>

            <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span>Mostrando</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ (actividads.current_page - 1) * actividads.per_page + 1 }}
                    </span>
                    <span>a</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ Math.min(actividads.current_page * actividads.per_page, actividads.total) }}
                    </span>
                    <span>de</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ actividads.total }}</span>
                    <span>actividades</span>
                </div>

                <Paginator 
                    :rows="actividads.per_page"
                    :totalRecords="actividads.total"
                    :first="(actividads.current_page - 1) * actividads.per_page"
                    :rowsPerPageOptions="[10, 15, 20, 30, 50]"
                    @page="onPageChange"
                    template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    class="paginator-custom"
                />
            </div>
        </div>

        <Modal v-model="showModal" :title="actividad?.id ? 'Editar Actividad' : 'Agregar Actividad'">
            <Form :actividad="actividad" @close="showModal = false" />
        </Modal>
    </AppLayout>

</template>
