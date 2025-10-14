<template>

    <Head title="Users" />

    <AppLayout :breadcrumbs>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Datatable :data="users" :columns :pagination="{
                rowsPerPage: 10,
            }">

            </Datatable>
        </div>

        <Modal v-model="showAssignRoleModal" title="Asignar Rol" @close="closeAssignRoleModal">

        </Modal>
    </AppLayout>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { User, BreadcrumbItem } from "@/types";
import { columns } from "./Columns";
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Datatable from '@/components/Table/Datatable.vue';
import Modal from '@/components/Modal.vue';

interface Props {
    users: User[];
}

defineProps<Props>();

const showAssignRoleModal = ref(false);
const selectedUser = ref<User | null>(null);
// const roles = ref<Role[]>([]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Usuarios",
        href: "/users",
    },
];

const openAssignRoleModal = (user: User) => {
    selectedUser.value = user;
    showAssignRoleModal.value = true;
};

const closeAssignRoleModal = () => {
    showAssignRoleModal.value = false;
    selectedUser.value = null;
};

// Obtener roles disponibles
const fetchRoles = async () => {
    try {
        const response = await axios.get('/roles/getAll');
        // roles.value = response.data.roles || [];
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

onMounted(() => {
    fetchRoles();
});
</script>
