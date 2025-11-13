<script setup lang="ts">
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';
import Input from '@/components/Input.vue';
import Button from 'primevue/button';

interface Props {
    modelValue: boolean;
    solicitudNumero?: string;
}

interface Emits {
    (e: 'update:modelValue', value: boolean): void;
    (e: 'confirm', razon: string): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const razonCancelacion = ref('');
const error = ref('');

const handleConfirm = () => {
    if (!razonCancelacion.value.trim()) {
        error.value = 'Debes proporcionar una razón para cancelar la solicitud';
        return;
    }
    
    emit('confirm', razonCancelacion.value);
    handleClose();
};

const handleClose = () => {
    razonCancelacion.value = '';
    error.value = '';
    emit('update:modelValue', false);
};
</script>

<template>
    <Modal 
        :model-value="modelValue" 
        @update:model-value="handleClose"
        title="Cancelar Solicitud"
        width="500px"
    >
        <div class="flex flex-col gap-4">
            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="pi pi-exclamation-triangle text-yellow-600 dark:text-yellow-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1">
                            ¿Estás seguro de cancelar esta solicitud?
                        </h4>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            <span v-if="solicitudNumero">La solicitud #{{ solicitudNumero }} </span>
                            <span>será marcada como <strong>Finalizada</strong> y no podrá revertirse esta acción.</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Razón de cancelación <span class="text-red-500">*</span>
                </label>
                <Input
                    v-model="razonCancelacion"
                    type="textarea"
                    :textAreaRows="4"
                    placeholder="Describe brevemente por qué se cancela esta solicitud..."
                    :error="error"
                    @input="error = ''"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Esta información quedará registrada en el sistema.
                </p>
            </div>
        </div>

        <template #footer>
            <div class="flex justify-end gap-3">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="handleClose"
                />
                <Button
                    label="Confirmar Cancelación"
                    severity="danger"
                    icon="pi pi-check"
                    @click="handleConfirm"
                />
            </div>
        </template>
    </Modal>
</template>
