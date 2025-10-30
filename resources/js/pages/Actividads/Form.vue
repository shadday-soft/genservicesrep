<script setup lang="ts">
import Input from '@/components/Input.vue';
import ActividadService from '@/Services/ActividadsService';
import { Button } from 'primevue';
import InputSwitch from 'primevue/inputswitch';
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

            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Activo</label>
                <InputSwitch v-model="form.active" :binary="true">
                    <template #handle="{ checked }">
                        <i :class="['!text-xs pi', { 'pi-check': checked, 'pi-times': !checked }]" />
                    </template>
                </InputSwitch>
                <small v-if="form.errors.active" class="text-red-500">{{ form.errors.active }}</small>
            </div>

            <div class="mt-6 flex justify-end col-span-2">
                <Button type="submit" label="Guardar" icon="pi pi-save" :loading="actividadService.form.processing">
                </Button>
            </div>
        </form>
    </div>
</template>
