<script setup lang="ts">
import Input from '@/components/Input.vue';
import Client from '@/Services/ClientService';
import type { Client as ClientType } from '@/types/client';
import { Button } from 'primevue';
const emit = defineEmits(['close']);

interface Props {
    client: ClientType | null;
}

const props = defineProps<Props>();

const clientService = new Client(props.client);
const form = clientService.form;

</script>

<template>
    <div>
        <form @submit.prevent="clientService.submit(() => emit('close'))" class="grid grid-cols-2 gap-4">
            <Input v-model="form.enterprise_name" label="Nombre de la empresa" :error="form.errors.enterprise_name"></Input>
            <Input v-model="form.contact_name" label="Nombre del contacto" :error="form.errors.contact_name"></Input>
            <Input v-model="form.email" label="Correo electrónico" type="email" :error="form.errors.email"></Input>
            <Input v-model="form.phone_number" label="Teléfono" type="tel" :error="form.errors.phone_number"></Input>
            <Input v-model:numeric="form.nit" label="NIT" type="number" :error="form.errors.nit"></Input>

            <div class="mt-6 flex justify-end col-span-2">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="clientService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
