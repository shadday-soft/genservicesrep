<script setup lang="ts">
import Input from '@/components/Input.vue';
import MapPicker from '@/components/MapPicker.vue';
import SucursalService from '@/Services/SucursalsService';
import Client from '@/Services/ClientService';
import vueFilePond from 'vue-filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import type { Sucursal as SucursalType } from '@/types';
import type { Client as ClientTypes } from '@/types/client';
import { Button } from 'primevue';
import { onMounted, ref, useTemplateRef } from 'vue';
const emit = defineEmits(['close']);

interface Props {
    sucursal: SucursalType | null;
    allSucursales?: SucursalType[];
}

const FilePond = vueFilePond(FilePondPluginImagePreview);

const props = defineProps<Props>();

const sucursalService = new SucursalService(props.sucursal);
const clientService = new Client(null);

const clientsList = ref<ClientTypes[]>([]);
const pond = useTemplateRef("pond");
const coordinates = ref<{ latitude: number | null; longitude: number | null }>({
    latitude: props.sucursal?.latitude || null,
    longitude: props.sucursal?.longitude || null,
});

const form = sucursalService.form;
const myFiles = ref<any[]>([]);

function updatefiles() {
    myFiles.value = pond.value.getFiles();
    form.image = pond.value.getFiles()[0]?.file || null;
    console.log(form.image);
}

function updateCoordinates(value: { latitude: number | null; longitude: number | null }) {
    coordinates.value = value;
    form.latitude = value.latitude;
    form.longitude = value.longitude;
}

function updateAddress(address: string) {
    form.address = address;
}

onMounted(async () => {
    const response = await clientService.getClients();
    clientsList.value = Array.isArray(response) ? response : Object.values(response);
    if (props.sucursal && props.sucursal.image) {
        myFiles.value = ['/storage/' + props.sucursal.image];
    }
});

</script>

<template>
    <div>
        <form @submit.prevent="sucursalService.submit(() => emit('close'))" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Input v-model="form.client_id" type="select" label="Cliente" :error="form.errors.client_id"
                option-label="enterprise_name" option-value="id" :options="clientsList"></Input>
            <Input v-model="form.name" label="Nombre de la sucursal" :error="form.errors.name"></Input>
            <Input v-model="form.address" label="Dirección" :error="form.errors.address"></Input>
            <Input v-model="form.contact_name" label="Nombre del contacto" :error="form.errors.contact_name"></Input>
            <Input v-model="form.phone_number" label="Teléfono" :error="form.errors.phone_number"></Input>
            <Input v-model="form.email" label="Correo electrónico" :error="form.errors.email"></Input>
            
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Logo de la sucursal</label>
                <FilePond name="logo" ref="pond" v-model="form.image" :allow-multiple="false"
                    accepted-file-types="image/*" :files="myFiles" @updatefiles="updatefiles"
                    :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Explora</span>'" />
                <div v-if="form.errors.image" class="text-red-600 text-sm mt-1">{{ form.errors.image }}</div>
            </div>

            <div class="col-span-1 md:col-span-2">
                <MapPicker 
                    :model-value="coordinates"
                    @update:model-value="updateCoordinates"
                    @update:address="updateAddress"
                    :sucursales="props.allSucursales || []"
                    :current-sucursal-id="props.sucursal?.id"
                    label="Ubicación de la sucursal en el mapa"
                />
                <div v-if="form.errors.latitude" class="text-red-600 text-sm mt-1">{{ form.errors.latitude }}</div>
                <div v-if="form.errors.longitude" class="text-red-600 text-sm mt-1">{{ form.errors.longitude }}</div>
            </div>

            <div class="mt-6 flex justify-end col-span-1 md:col-span-2">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="sucursalService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
