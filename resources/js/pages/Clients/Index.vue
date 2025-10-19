<script setup lang="ts">
import { ref } from 'vue';
import type { Client } from '@/types/client';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { User, BreadcrumbItem } from "@/types";
import { columns } from "./Columns";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';

interface Props {
    clients: Client[];
}

const client = ref<Client | null>(null);

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Clientes",
        href: "/clients",
    },
];

const showModal = ref(false);

const addClient = () => {
    client.value = null;
    showModal.value = true;
};

const editClient = (clientData: Client) => {
    client.value = clientData;
    showModal.value = true;
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Clientes" />

        <div>
            <Datatable :columns="columns" :data="clients">
                <template #addButton>
                    <div class="flex gap-x-2">
                        <Button label="Agregar Cliente" icon="pi pi-plus" size="small" @click="addClient" />
                    </div>
                </template>
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text @click="editClient(data)" />
                    <!-- <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="questionDeleteMessage(route('equipos.destroy', data.id), 'Esta acción eliminará el equipo', 'Equipo')" /> -->
                </template>
            </Datatable>
        </div>

        <Modal v-model="showModal" title="Asignar Rol" >
            <Form :client="client" @close="showModal = false" />
        </Modal>
    </AppLayout>

</template>