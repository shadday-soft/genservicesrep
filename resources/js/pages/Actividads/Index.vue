<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { BreadcrumbItem, PaginatedResponse } from "@/types";
import type { Actividad } from '@/types';
import { columns } from "./Columns";
import { Head } from '@inertiajs/vue3';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';
import ActividadService from '@/Services/ActividadsService';

interface Props {
    actividads: PaginatedResponse<Actividad>;
}

const actividad = ref<Actividad | null>(null);

defineProps<Props>();

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
        </div>

        <Modal v-model="showModal" :title="actividad?.id ? 'Editar Actividad' : 'Agregar Actividad'">
            <Form :actividad="actividad" @close="showModal = false" />
        </Modal>
    </AppLayout>

</template>
