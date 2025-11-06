<script setup lang="ts">
import { ref } from 'vue';
import type { Tecnico } from '@/types';
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
import { watchDebounced } from '@vueuse/core';
import TecnicoService from '@/Services/TecnicosService';

interface Props {
    tecnicos: PaginatedResponse<Tecnico>;
    filters?: {
        search?: string;
        per_page?: number;
    };
}

const tecnico = ref<Tecnico | null>(null);
const tecnicoObject = new TecnicoService(tecnico.value);
const props = defineProps<Props>();

const searchQuery = ref(props.filters?.search || '');
const perPage = ref(props.filters?.per_page || 15);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Técnicos",
        href: "/tecnicos",
    },
];

const showModal = ref(false);

const addTecnico = () => {
    tecnico.value = null;
    showModal.value = true;
};

const editTecnico = (tecnicoData: Tecnico) => {
    tecnico.value = tecnicoData;
    showModal.value = true;
};

const onPageChange = (event: any) => {
    perPage.value = event.rows;
    performSearch(event.page + 1);
};

const performSearch = (page = 1) => {
    router.get('/tecnicos', {
        page: page,
        per_page: perPage.value,
        search: searchQuery.value || undefined
    }, {
        preserveState: true,
        preserveScroll: page !== 1,
        only: ['tecnicos']
    });
};

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
        <Head title="Técnicos" />

        <div>
            <div class="mb-4 flex items-center gap-3">
                <div class="flex-1">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <Input 
                            v-model="searchQuery" 
                            placeholder="Buscar técnicos..." 
                            class="w-full"
                        />
                    </IconField>
                </div>
                <Button label="Agregar Técnico" icon="pi pi-plus" size="small" @click="addTecnico" />
            </div>

            <Datatable :columns="columns" :data="tecnicos.data" :noShowHeader="true">
                <template #actions="{ data }">
                    <Button icon="pi pi-pencil" size="small" severity="warn" text @click="editTecnico(data)" />
                    <Button icon="pi pi-trash" size="small" severity="danger" text
                        @click="tecnicoObject.delete(data.id)" />
                </template>
            </Datatable>

            <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span>Mostrando</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ (tecnicos.current_page - 1) * tecnicos.per_page + 1 }}
                    </span>
                    <span>a</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ Math.min(tecnicos.current_page * tecnicos.per_page, tecnicos.total) }}
                    </span>
                    <span>de</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ tecnicos.total }}</span>
                    <span>técnicos</span>
                </div>

                <Paginator 
                    :rows="tecnicos.per_page"
                    :totalRecords="tecnicos.total"
                    :first="(tecnicos.current_page - 1) * tecnicos.per_page"
                    :rowsPerPageOptions="[10, 15, 20, 30, 50]"
                    @page="onPageChange"
                    template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                />
            </div>
        </div>

        <Modal v-model="showModal" width="80vw" :title="tecnico ? 'Editar Técnico' : 'Agregar Técnico'">
            <Form :tecnico="tecnico" @close="showModal = false" />
        </Modal>
    </AppLayout>
</template>
