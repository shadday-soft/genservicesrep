<script setup lang="ts">
import Input from '@/components/Input.vue';
import ActividadService from '@/Services/ActividadsService';
import { Button } from 'primevue';
import { ref } from 'vue';
const emit = defineEmits(['close']);

interface Props {
    actividad: import('@/types').Actividad | null;
}

const props = defineProps<Props>();

const actividadService = new ActividadService(props.actividad);
const form = actividadService.form;

</script>

<template>
    <div>
        <form @submit.prevent="actividadService.submit(() => emit('close'))" class="grid grid-cols-2 gap-4">
            <Input v-model="form.nombre" label="Nombre" :error="form.errors.nombre"></Input>
           
            <Input v-model="form.active" type="select" label="Activo" :error="form.errors.active"
                :options="[{ label: 'Sí', value: 1 }, { label: 'No', value: 0 }]" option-label="label" option-value="value"></Input>

            <div class="mt-6 flex justify-end col-span-2">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="actividadService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
