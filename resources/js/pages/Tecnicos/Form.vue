<script setup lang="ts">
import { ref } from 'vue';
import Input from '@/components/Input.vue';
import SignaturePad from '@/components/SignaturePad.vue';
import TecnicoService from '@/Services/TecnicosService';
import type { Tecnico } from '@/types';
import { Button } from 'primevue';
import FileUpload from 'primevue/fileupload';
import Select from 'primevue/select';
import DatePicker from 'primevue/datepicker';
import InputSwitch from 'primevue/inputswitch';

const emit = defineEmits(['close']);

interface Props {
    tecnico: Tecnico | null;
}

const props = defineProps<Props>();

const tecnicoService = new TecnicoService(props.tecnico);
const form = tecnicoService.form;

const tiposSangre = [
    { label: 'A+', value: 'A+' },
    { label: 'A-', value: 'A-' },
    { label: 'B+', value: 'B+' },
    { label: 'B-', value: 'B-' },
    { label: 'AB+', value: 'AB+' },
    { label: 'AB-', value: 'AB-' },
    { label: 'O+', value: 'O+' },
    { label: 'O-', value: 'O-' },
];

const tiposContrato = [
    { label: 'Indefinido', value: 'Indefinido' },
    { label: 'Fijo', value: 'Fijo' },
    { label: 'Obra o labor', value: 'Obra o labor' },
    { label: 'Prestación de servicios', value: 'Prestación de servicios' },
];

const fotoPreview = ref<string | null>(
    props.tecnico?.foto ? `/uploads/${props.tecnico.foto}` : null
);

const onFileSelect = (event: any) => {
    const file = event.files[0];
    if (file) {
        form.foto = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            fotoPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const onFileRemove = () => {
    form.foto = null;
    fotoPreview.value = null;
};
</script>

<template>
    <div>
        <form @submit.prevent="tecnicoService.submit(() => emit('close'))" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Foto -->
            <div class="col-span-full">
                <label class="block text-sm font-medium mb-2">Foto</label>
                <div class="flex items-center gap-4">
                    <div v-if="fotoPreview" class="relative">
                        <img :src="fotoPreview" alt="Preview" class="w-24 h-24 rounded-lg object-cover" />
                        <button
                            type="button"
                            @click="onFileRemove"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"
                        >
                            <i class="pi pi-times text-xs"></i>
                        </button>
                    </div>
                    <FileUpload
                        mode="basic"
                        accept="image/*"
                        :maxFileSize="2000000"
                        @select="onFileSelect"
                        chooseLabel="Seleccionar Foto"
                        class="flex-1"
                    />
                </div>
                <p v-if="form.errors.foto" class="mt-1 text-sm text-red-600">{{ form.errors.foto }}</p>
            </div>

            <!-- Datos personales -->
            <Input v-model="form.nombre_completo" label="Nombre Completo" required :error="form.errors.nombre_completo" class="col-span-full" />
            <Input v-model="form.identificacion" label="Identificación" required :error="form.errors.identificacion" />
            <Input v-model="form.correo" label="Correo Electrónico" type="email" required :error="form.errors.correo" />
            
            <!-- Información de salud -->
            <div>
                <label class="block text-sm font-medium mb-2">Tipo de Sangre (RH)</label>
                <Select 
                    v-model="form.tipo_sangre" 
                    :options="tiposSangre"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Seleccionar tipo de sangre"
                    class="w-full"
                />
                <p v-if="form.errors.tipo_sangre" class="mt-1 text-sm text-red-600">{{ form.errors.tipo_sangre }}</p>
            </div>
            <Input v-model="form.eps" label="EPS" :error="form.errors.eps" />
            
            <!-- Información de contacto -->
            <Input v-model="form.persona_contacto" label="Persona de Contacto" :error="form.errors.persona_contacto" class="col-span-full md:col-span-1" />
            <Input v-model="form.telefono_contacto" label="Teléfono de Contacto" :error="form.errors.telefono_contacto" class="col-span-full md:col-span-1" />
            <Input v-model="form.direccion_contacto" label="Dirección de Contacto" :error="form.errors.direccion_contacto" class="col-span-full" />
            
            <div class="w-full">
                <label class="block text-sm font-medium mb-2">Fecha de Nacimiento</label>
                <DatePicker 
                    v-model="form.fecha_nacimiento" 
                    dateFormat="yy-mm-dd"
                    placeholder="Seleccionar fecha"
                    :maxDate="new Date()"
                    class="w-full"
                    showIcon
                />
                <p v-if="form.errors.fecha_nacimiento" class="mt-1 text-sm text-red-600">{{ form.errors.fecha_nacimiento }}</p>
            </div>
            
            <!-- Información contractual -->
            <div>
                <label class="block text-sm font-medium mb-2">Tipo de Contrato *</label>
                <Select 
                    v-model="form.tipo_contrato" 
                    :options="tiposContrato"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Seleccionar tipo de contrato"
                    class="w-full"
                />
                <p v-if="form.errors.tipo_contrato" class="mt-1 text-sm text-red-600">{{ form.errors.tipo_contrato }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Fecha Inicio Contrato *</label>
                <DatePicker 
                    v-model="form.fecha_inicio_contrato" 
                    dateFormat="yy-mm-dd"
                    placeholder="Seleccionar fecha"
                    class="w-full"
                    showIcon
                />
                <p v-if="form.errors.fecha_inicio_contrato" class="mt-1 text-sm text-red-600">{{ form.errors.fecha_inicio_contrato }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Fecha Fin Contrato</label>
                <DatePicker 
                    v-model="form.fecha_fin_contrato" 
                    dateFormat="yy-mm-dd"
                    placeholder="Seleccionar fecha"
                    class="w-full"
                    showIcon
                />
                <p v-if="form.errors.fecha_fin_contrato" class="mt-1 text-sm text-red-600">{{ form.errors.fecha_fin_contrato }}</p>
            </div>

            <!-- Estado del técnico -->
            <div class="col-span-full flex flex-col gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Estado del Técnico
                </label>
                <div class="flex items-center gap-3">
                    <InputSwitch v-model="form.activo" :binary="true" />
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ form.activo ? 'Técnico Activo (puede iniciar sesión)' : 'Técnico Bloqueado (no puede iniciar sesión)' }}
                    </span>
                </div>
                <p v-if="form.errors.activo" class="mt-1 text-sm text-red-600">{{ form.errors.activo }}</p>
            </div>

            <!-- Firma predeterminada -->
            <div class="col-span-full">
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                    Firma Predeterminada del Técnico
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                    Esta firma se precargará automáticamente al generar informes
                </p>
                <SignaturePad 
                    v-model="form.firma"
                    :error="form.errors.firma"
                />
            </div>

            <div class="mt-6 flex justify-end col-span-full gap-2">
                <Button type="button" label="Cancelar" severity="secondary" @click="emit('close')" />
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="tecnicoService.form.processing" />
            </div>
        </form>
    </div>
</template>
