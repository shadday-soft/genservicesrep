<script setup lang="ts">
import { ref } from 'vue';
import type { Client } from '@/types/client';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { User, BreadcrumbItem, Sucursal } from "@/types";
import { columns } from "./Columns";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';
import SucursalService from '@/Services/SucursalsService';



interface Props {
    sucursals: Sucursal[];
}

const sucursal = ref<Sucursal | null>(null);

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Sucursales",
        href: "/sucursals",
    },
];

const showModal = ref(false);

const add = () => {
    sucursal.value = null;
    showModal.value = true;
};

const sucursalService = new SucursalService(sucursal.value);

const edit = (sucursalData: Sucursal) => {
    sucursal.value = sucursalData;
    showModal.value = true;
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Sucursales" />

        <div>
            <Datatable :columns="columns" :data="sucursals">
                <template #addButton>
                    <div class="flex gap-x-2">
                        <Button label="Agregar Sucursal" icon="pi pi-plus" size="small" @click="add" />
                    </div>
                </template>
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text v-tooltip.left="`Editar`" @click="edit(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="sucursalService.delete(data.id)" />
                </template>
            </Datatable>
        </div>

        <Modal v-model="showModal" :title="sucursal?.id ? 'Editar Sucursal' : 'Agregar Sucursal'">
            <Form :sucursal="sucursal" @close="showModal = false" />
        </Modal>
    </AppLayout>

</template>