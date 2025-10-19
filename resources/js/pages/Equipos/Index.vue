<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { BreadcrumbItem } from "@/types";
import { columns } from "./Columns";
import { Head } from '@inertiajs/vue3';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';
import EquipoService from '@/Services/EquiposService';

interface Props {
    equipos: import('@/types').Equipo[];
}

const equipo = ref<import('@/types').Equipo | null>(null);

defineProps<Props>();

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

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Equipos" />

        <div>
            <Datatable :columns="columns" :data="equipos">
                <template #addButton>
                    <div class="flex gap-x-2">
                        <Button label="Agregar Equipo" icon="pi pi-plus" size="small" @click="add" />
                    </div>
                </template>
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text v-tooltip.left="`Editar`" @click="edit(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="equipoService.delete(data.id)" />
                </template>
            </Datatable>
        </div>

        <Modal v-model="showModal" :title="equipo?.id ? 'Editar Equipo' : 'Agregar Equipo'" width="80vw" :maximizable="true">
            <Form :equipo="equipo" @close="showModal = false" />
        </Modal>
    </AppLayout>

</template>
