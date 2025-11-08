<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import SignaturePad from 'signature_pad';
import { Button } from 'primevue';

interface Props {
    modelValue?: string;
    label?: string;
    error?: string;
    width?: number;
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    label: '',
    error: '',
    width: 500,
    height: 200
});

const emit = defineEmits(['update:modelValue']);

const canvas = ref<HTMLCanvasElement | null>(null);
let signaturePad: SignaturePad | null = null;

onMounted(() => {
    if (canvas.value) {
        signaturePad = new SignaturePad(canvas.value, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 2.5,
        });

        // Si hay un valor inicial, cargarlo
        if (props.modelValue) {
            signaturePad.fromDataURL(props.modelValue);
        }

        // Emitir cambios cuando el usuario firma
        signaturePad.addEventListener('endStroke', () => {
            if (signaturePad && !signaturePad.isEmpty()) {
                emit('update:modelValue', signaturePad.toDataURL());
            }
        });

        // Ajustar el tamaño del canvas
        resizeCanvas();
    }
});

const resizeCanvas = () => {
    if (!canvas.value || !signaturePad) return;

    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.value.width = canvas.value.offsetWidth * ratio;
    canvas.value.height = canvas.value.offsetHeight * ratio;
    canvas.value.getContext('2d')?.scale(ratio, ratio);
    
    // Redibujar si hay datos
    if (props.modelValue) {
        signaturePad.fromDataURL(props.modelValue);
    }
};

const clear = () => {
    if (signaturePad) {
        signaturePad.clear();
        emit('update:modelValue', '');
    }
};

// Cargar firma cuando cambia el valor externo
watch(() => props.modelValue, (newValue) => {
    if (signaturePad && newValue && signaturePad.isEmpty()) {
        signaturePad.fromDataURL(newValue);
    }
});
</script>

<template>
    <div class="flex flex-col gap-2">
        <label v-if="label" class="text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
        </label>
        
        <div class="relative">
            <canvas
                ref="canvas"
                :style="{ width: width + 'px', height: height + 'px' }"
                class="border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-crosshair touch-none bg-white"
            />
            
            <Button
                type="button"
                icon="pi pi-times"
                severity="danger"
                size="small"
                class="absolute !h-5"
                @click="clear"
                title="Limpiar firma"
            />
        </div>

        <span v-if="error" class="text-xs italic text-red-500">
            {{ error }}
        </span>
    </div>
</template>

<style scoped>
canvas {
    display: block;
}
</style>
