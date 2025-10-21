<script setup lang="ts">
import { ref } from 'vue';
import type { Client } from '@/types/client';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import Paginator from 'primevue/paginator';
import Input from '@/components/Input.vue';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import type { User, BreadcrumbItem, PaginatedResponse } from "@/types";
import { columns } from "./Columns";
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import Datatable from '@/components/Table/Datatable.vue';
import Form from './Form.vue';
import Modal from '@/components/Modal.vue';
import { watchDebounced } from '@vueuse/core';
import ClientService from '@/Services/ClientService';

interface Props {
    clients: PaginatedResponse<Client>;
    filters?: {
        search?: string;
        per_page?: number;
    };
}

const client = ref<Client | null>(null);


const clientObject = new ClientService(client.value);

const props = defineProps<Props>();

const searchQuery = ref(props.filters?.search || '');
const perPage = ref(props.filters?.per_page || 15);

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

const onPageChange = (event: any) => {
    perPage.value = event.rows;
    performSearch(event.page + 1);
};

const performSearch = (page = 1) => {
    router.get('/clients', {
        page: page,
        per_page: perPage.value,
        search: searchQuery.value || undefined
    }, {
        preserveState: true,
        preserveScroll: page !== 1,
        only: ['clients']
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

        <Head title="Clientes" />

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
                            placeholder="Buscar clientes..." 
                            class="w-full"
                        />
                    </IconField>
                </div>
                <Button label="Agregar Cliente" icon="pi pi-plus" size="small" @click="addClient" />
            </div>

            <Datatable :columns="columns" :data="clients.data" :noShowHeader="true">
                <template #addButton>
                    <!-- Removido porque ahora está arriba -->
                </template>
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text @click="editClient(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="clientObject.delete(data.id)" />
                </template>
            </Datatable>

            <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span>Mostrando</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ (clients.current_page - 1) * clients.per_page + 1 }}
                    </span>
                    <span>a</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ Math.min(clients.current_page * clients.per_page, clients.total) }}
                    </span>
                    <span>de</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ clients.total }}</span>
                    <span>clientes</span>
                </div>

                <Paginator 
                    :rows="clients.per_page"
                    :totalRecords="clients.total"
                    :first="(clients.current_page - 1) * clients.per_page"
                    :rowsPerPageOptions="[10, 15, 20, 30, 50]"
                    @page="onPageChange"
                    template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    class="paginator-custom"
                />
            </div>
        </div>

        <Modal v-model="showModal" :title="client ? 'Editar Cliente' : 'Agregar Cliente'">
            <Form :client="client" @close="showModal = false" />
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