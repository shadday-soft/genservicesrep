<script setup lang="ts">
import Input from '@/components/Input.vue';
import RichTextEditor from '@/components/RichTextEditor.vue';
import SolicitudService from '@/Services/SolicitudsService';
import Client from '@/Services/ClientService';
import SucursalService from '@/Services/SucursalsService';
import EquipoService from '@/Services/EquiposService';
import ActividadService from '@/Services/ActividadsService';
import vueFilePond from 'vue-filepond';
import type { Solicitud as SolicitudType, Sucursal, Equipo, User, Actividad } from '@/types';
import type { Client as ClientTypes } from '@/types/client';
import UserService from '@/Services/UserService';
import FilePondPluginPdfPreview from "filepond-plugin-pdf-preview";
import { Button } from 'primevue';
import { onMounted, ref, useTemplateRef, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
const emit = defineEmits(['close']);

interface Props {
    solicitud: SolicitudType | null;
    tipo?: string;
}

const FilePond = vueFilePond(FilePondPluginPdfPreview);

const props = defineProps<Props>();

const solicitudService = new SolicitudService(props.solicitud);
const clientService = new Client(null);
const sucursalService = new SucursalService(null);
const equipoService = new EquipoService(null);
const actividadService = new ActividadService(null);
const userService = new UserService();

const clientsList = ref<ClientTypes[]>([]);
const sucursalesList = ref<Sucursal[]>([]);
const equiposList = ref<Equipo[]>([]);
const tecnicosList = ref<User[]>([]);
const actividadesList = ref<Actividad[]>([]);
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
    { label: 'Finalizada', value: 'Finalizada' },
];

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
    // Cargar clientes, técnicos y actividades
    clientsList.value = await clientService.getClients();
    if(usePage().props.auth.user.client){
        form.client_id = usePage().props.auth.user.client.id;
    }else{
        tecnicosList.value = (await userService.getUsers()).filter((user: User) => user.role === 'Tecnico');
    }

    actividadesList.value = await actividadService.getActividades();

    // Asignar tipo_mantenimiento basado en el prop tipo
    if (props.tipo) {
        form.tipo_mantenimiento = props.tipo;
    }

    if (props.solicitud) {
        if (props.solicitud.client_id) {
            sucursalesList.value = await sucursalService.getSucursals();
        }
        if (props.solicitud.sucursal_id) {
            const equiposResponse = await equipoService.getEquipos(props.solicitud.sucursal_id);
            equiposList.value = equiposResponse.filter((e: Equipo) => e.sucursal_id === props.solicitud!.sucursal_id);
            const sucursal = sucursalesList.value.find(s => s.id === props.solicitud!.sucursal_id);
            form.ubicacion = sucursal ? sucursal.address : '';
            form.telefono = sucursal ? sucursal.phone_number : '';
            form.mail = sucursal ? sucursal.email : '';
        }
        if (props.solicitud.orden_trabajo) {
            myFiles.value = [props.solicitud.orden_trabajo];
        }
    }
});

</script>

<template>
    <div>
        <form @submit.prevent="solicitudService.submit(() => emit('close'))"
            class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Empresa -->
            <Input v-model="form.client_id" :disabled="$page.props.auth.user.client" type="select" label="Empresa" :error="form.errors.client_id"
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
                option-label="nombre" option-value="nombre" :options="actividadesList"></Input>

            <!-- Estado -->
            <Input v-model="form.estado" type="select" v-if="props.solicitud" label="Estado de solicitud" :error="form.errors.estado"
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

            <div class="col-span-1 md:col-span-3 flex flex-col  justify-between gap-x-4">
                <RichTextEditor 
                    v-model="form.detalles" 
                    label="Detalles"
                    :error="form.errors.detalles"
                    placeholder="Describe los detalles de la solicitud..."
                />

                <!-- Archivo PDF -->
                <div class="w-full mt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Orden de compra</label>
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
