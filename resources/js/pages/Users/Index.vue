<template>

    <Head title="Users" />
    <AppLayout :breadcrumbs>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <Datatable :data="users" :columns :pagination="{
                rowsPerPage: 10,
            }">
                <template #actions="{ data }">
                    <div class="flex gap-2 items-center">
                        <button v-if="validateRole('Administrador') || $page.props.auth.user.id == 1" class="p-button p-component p-button-text text-sm"
                            type="button" @click="openAssignRoleModal(data)">
                            <i class="pi pi-user-plus mr-2"></i>Asignar Rol
                        </button>
                    </div>
                </template>
            </Datatable>
        </div>

        <Modal v-model="showAssignRoleModal" title="Asignar Rol" @close="closeAssignRoleModal">
            <div class="space-y-4">
                <Input v-model="selectedRole" label="Rol" type="select"
                    :options="availableRoles.map(r => ({ label: r, value: r }))" placeholder="Selecciona un rol" />

                <div class="flex justify-end gap-2">
                    <Button type="button" severity="secondary" label="Cancelar" @click="closeAssignRoleModal" />
                    <Button type="button" label="Guardar" icon="pi pi-save" @click="assignRole" />
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from "@/layouts/AppLayout.vue";
import Button from 'primevue/button';
import type { User, BreadcrumbItem } from "@/types";
import { columns } from "./Columns";
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import Datatable from '@/components/Table/Datatable.vue';
import Modal from '@/components/Modal.vue';
import Input from '@/components/Input.vue';
import { validateRole } from '@/composables/useCommonUtilities';

interface Props {
    users: User[];
}

defineProps<Props>();

const showAssignRoleModal = ref(false);
const selectedUser = ref<User | null>(null);
const selectedRole = ref<string>('Cliente');
const availableRoles = [
    'Cliente',
    'Tecnico',
    'Administrador',
];
// const roles = ref<Role[]>([]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Usuarios",
        href: "/users",
    },
];

const openAssignRoleModal = (user: User) => {
    selectedUser.value = user;
    selectedRole.value = (user as any).role ?? 'Cliente';
    showAssignRoleModal.value = true;
};

const closeAssignRoleModal = () => {
    showAssignRoleModal.value = false;
    selectedUser.value = null;
    selectedRole.value = 'Cliente';
};



const assignRole = async () => {
    if (!selectedUser.value) return;
    try {
        const { data } = await axios.put(`/users/${selectedUser.value.id}/role`, { role: selectedRole.value });
        showAssignRoleModal.value = false;
    } catch (error) {
        console.error('Error assigning role:', error);
    }
};
</script>
