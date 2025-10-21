<script setup lang="ts">
import Input from '@/components/Input.vue';
import SolicitudService from '@/Services/SolicitudsService';
import Client from '@/Services/ClientService';
import SucursalService from '@/Services/SucursalsService';
import EquipoService from '@/Services/EquiposService';
import vueFilePond from 'vue-filepond';
import type { Solicitud as SolicitudType, Sucursal, Equipo, User } from '@/types';
import type { Client as ClientTypes } from '@/types/client';
import UserService from '@/Services/UserService';
import { Button } from 'primevue';
import { onMounted, ref, useTemplateRef, watch } from 'vue';
const emit = defineEmits(['close']);

interface Props {
    solicitud: SolicitudType | null;
}

const FilePond = vueFilePond();

const props = defineProps<Props>();

const solicitudService = new SolicitudService(props.solicitud);
const clientService = new Client(null);
const sucursalService = new SucursalService(null);
const equipoService = new EquipoService(null);
const userService = new UserService();

const clientsList = ref<ClientTypes[]>([]);
const sucursalesList = ref<Sucursal[]>([]);
const equiposList = ref<Equipo[]>([]);
const tecnicosList = ref<User[]>([]);
const pond = useTemplateRef<any>("pond");

const form = solicitudService.form;
const myFiles = ref<any[]>([]);

const prioridadOptions = [
    { label: 'Normal', value: 'Normal' },
    { label: 'Intermedio', value: 'Intermedio' },
    { label: 'Urgente', value: 'Urgente' },
];

const estadoOptions = [
    { label: 'Nueva', value: 'Nueva' },
    { label: 'Proceso', value: 'Proceso' },
    { label: 'Revisión', value: 'Revisión' },
    { label: 'Finalizada', value: 'Finalizada' },
    { label: 'Anulada', value: 'Anulada' },
    { label: 'Programada', value: 'Programada' },
];

const actividadOptions = [
    { label: 'Mantenimiento preventivo', value: 'Mantenimiento preventivo' },
    { label: 'Atención de emergencia', value: 'Atención de emergencia' },
    { label: 'Montaje de equipos', value: 'Montaje de equipos' },
    { label: 'Cambio de insumos', value: 'Cambio de insumos' },
    { label: 'Inspección', value: 'Inspección' },
    { label: 'Mantenimiento Transferencia', value: 'Mantenimiento Transferencia' },
    { label: 'Microfiltrado', value: 'Microfiltrado' },
    { label: 'Soporte Técnico', value: 'Soporte Técnico' },
    { label: 'Mantenimiento Eléctrico', value: 'Mantenimiento Eléctrico' },
    { label: 'Mantenimiento Sub-estación', value: 'Mantenimiento Sub-estación' },
    { label: 'Mantenimiento Transformadores', value: 'Mantenimiento Transformadores' },
    { label: 'Mantenimiento Correctivo', value: 'Mantenimiento Correctivo' },
    { label: 'Cambio de Control', value: 'Cambio de Control' },
    { label: 'Instalación de Tanque', value: 'Instalación de Tanque' },
    { label: 'Correctivo Mecánico', value: 'Correctivo Mecánico' },
    { label: 'Mantenimiento Tuberías de Escape', value: 'Mantenimiento Tuberías de Escape' },
    { label: 'Lavados Generador-Radiador-Motor', value: 'Lavados Generador-Radiador-Motor' },
    { label: 'Cambio de Baterías', value: 'Cambio de Baterías' },
    { label: 'Otro', value: 'Otro' },
]

function updatefiles() {
    if (pond.value) {
        myFiles.value = pond.value.getFiles();
        form.orden_trabajo = pond.value.getFiles()[0]?.file || null;
    }
}

watch(() => form.client_id, async (newEmpresaId) => {
    if (newEmpresaId) {
        sucursalesList.value = await sucursalService.getSucursals();
    } else {
        sucursalesList.value = [];
    }
    form.sucursal_id = '';
    form.equipo_id = '';
});

// Watch para cargar equipos cuando se selecciona una sucursal
watch(() => form.sucursal_id, async (newSucursalId) => {
    form.equipo_id = '';
    if (newSucursalId) {
        equiposList.value = await equipoService.getEquipos(newSucursalId);
        const sucursal = sucursalesList.value.find(s => s.id === newSucursalId);
        form.ubicacion = sucursal ? sucursal.address : '';
        form.telefono = sucursal ? sucursal.phone_number : '';
        form.mail = sucursal ? sucursal.email : '';

    } else {
        equiposList.value = [];
    }
});

onMounted(async () => {
    // Cargar clientes
    clientsList.value = await clientService.getClients();
    tecnicosList.value = await userService.getUsers();
    if (props.solicitud) {
        if (props.solicitud.client_id) {
            sucursalesList.value = await sucursalService.getSucursals();
        }
        if (props.solicitud.sucursal_id) {
            const equiposResponse = await equipoService.getEquipos(props.solicitud.sucursal_id);
            equiposList.value = equiposResponse.filter((e: Equipo) => e.sucursal_id === props.solicitud!.sucursal_id);
        }
        if (props.solicitud.orden_trabajo) {
            myFiles.value = ['/storage/' + props.solicitud.orden_trabajo];
        }
    }
});

</script>

<template>
    <div>
        <form @submit.prevent="solicitudService.submit(() => emit('close'))" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Número de Orden (Autogenerado) -->
            <!-- <Input v-model="form.numero_orden" label="Número de Orden" :error="form.errors.numero_orden"
                placeholder="Generado automáticamente" disabled></Input> -->

            <!-- Empresa -->
            <Input v-model="form.client_id" type="select" label="Empresa" :error="form.errors.client_id"
                option-label="enterprise_name" option-value="id" :options="clientsList"></Input>

            <!-- Sucursal -->
            <Input v-model="form.sucursal_id" type="select" label="Sucursal" :error="form.errors.sucursal_id"
                option-label="name" option-value="id" :options="sucursalesList" :disabled="!form.client_id"></Input>

            <!-- Equipo -->
            <Input v-model="form.equipo_id" type="select" label="Equipo" :error="form.errors.equipo_id"
                option-label="nombre_equipo" option-value="id" :options="equiposList"
                :disabled="!form.sucursal_id"></Input>

            <!-- Prioridad -->
            <Input v-model="form.prioridad" type="select" label="Prioridad de solicitud" :error="form.errors.prioridad"
                option-label="label" option-value="value" :options="prioridadOptions"></Input>
            <Input v-model="form.actividad" type="select" label="Actividad" :error="form.errors.actividad"
                option-label="label" option-value="value" :options="actividadOptions"></Input>

            <!-- Estado -->
            <Input v-model="form.estado" type="select" label="Estado de solicitud" :error="form.errors.estado"
                option-label="label" option-value="value" :options="estadoOptions"></Input>

            <!-- Técnico Asignado -->
            <Input v-model="form.user_id" type="select" label="Asignar a Técnico" :error="form.errors.user_id"
                option-label="name" option-value="id" :options="tecnicosList"></Input>

            <!-- Fecha de Mantenimiento -->
            <!-- <Input v-model="form.fecha_mantenimiento" type="date" label="Fecha de Mantenimiento"
                :error="form.errors.fecha_mantenimiento"></Input> -->

            <!-- Fecha Programada -->
            <Input v-model:datetime="form.fecha_programada" type="datetime" label="Fecha programada"
                :error="form.errors.fecha_programada"></Input>

            <Input v-model="form.telefono" label="Teléfono" :error="form.errors.telefono"></Input>

            <Input v-model="form.mail" label="Mail" :error="form.errors.mail" type="email"></Input>

            <Input v-model="form.ubicacion" label="Ubicación" :error="form.errors.ubicacion"></Input>

            <!-- Quien Solicita -->
            <Input v-model="form.quien_solicita" label="¿Quién solicita?" :error="form.errors.quien_solicita"></Input>

            <div class="col-span-1 md:col-span-3 flex flex-col md:flex-row  justify-between gap-x-4">
                <Input v-model="form.detalles" class="w-full" type="textarea" label="Detalles" :error="form.errors.detalles"
                    :textAreaRows="3"></Input>


                <!-- Archivo PDF -->
                <div class="col-span-1 md:col-span-2 w-full">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Archivo PDF (Orden de trabajo)</label>
                    <FilePond name="orden_trabajo" ref="pond" v-model="form.orden_trabajo" :allow-multiple="false"
                        accepted-file-types="application/pdf" :files="myFiles" @updatefiles="updatefiles"
                        :label-idle="'Arrastra y suelta tu archivo o <span class=\'filepond--label-action\'>Examinar</span>'" />
                    <div v-if="form.errors.orden_trabajo" class="text-red-600 text-sm mt-1">{{ form.errors.orden_trabajo
                        }}
                    </div>
                </div>
            </div>



            <div class="mt-6 flex justify-end col-span-1 md:col-span-2">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="solicitudService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
