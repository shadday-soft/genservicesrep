<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { BreadcrumbItem, Solicitud } from "@/types";
import { columns } from "./Columns";
import { Head } from '@inertiajs/vue3';
import Datatable from '@/components/Table/Datatable.vue';

import Modal from '@/components/Modal.vue';
import SolicitudService from '@/Services/SolicitudsService';
import Form from '@/pages/Solicituds/Form.vue';



interface Props {
    solicituds: Solicitud[];
}

const solicitud = ref<Solicitud | null>(null);

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Solicitudes",
        href: "/solicituds",
    },
];

const showModal = ref(false);

const add = () => {
    solicitud.value = null;
    showModal.value = true;
};

const solicitudService = new SolicitudService(solicitud.value);

const edit = (solicitudData: Solicitud) => {
    solicitud.value = solicitudData;
    showModal.value = true;
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Solicitudes" />

        <div>
            <Datatable :columns="columns" :data="solicituds" >
                <template #addButton>
                    <div class="flex gap-x-2">
                        <Button label="Agregar Solicitud" icon="pi pi-plus" size="small" @click="add" />
                    </div>
                </template>
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text v-tooltip.left="`Editar`" @click="edit(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="solicitudService.delete(data.id)" />
                </template>
            </Datatable>
        </div>

        <Modal v-model="showModal" :title="solicitud?.id ? 'Editar Solicitud' : 'Agregar Solicitud'" :maximizable="true" width="80vw">
            <Form :solicitud="solicitud" @close="showModal = false" />
        </Modal>
    </AppLayout>

</template>
