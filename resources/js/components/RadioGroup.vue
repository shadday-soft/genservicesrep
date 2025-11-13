<template>
    <div class="flex flex-col gap-2 bg-gray-200 px-3 py-2 rounded-md">
        <label v-if="label" class="text-sm font-bold text-gray-900 dark:text-gray-300">
            {{ label }}
        </label>
        <div class="flex flex-wrap gap-3">
            <div v-for="option in options" :key="uniqueId + '_' + option" class="flex items-center gap-2">
                <RadioButton 
                    :modelValue="modelValue" 
                    @update:modelValue="$emit('update:modelValue', $event)"
                    :inputId="uniqueId + '_' + option" 
                    :value="option"
                />
                <label :for="uniqueId + '_' + option" class="text-sm cursor-pointer">{{ option }}</label>
            </div>
        </div>
        <span v-if="error" class="text-xs italic text-red-500">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
import RadioButton from 'primevue/radiobutton';

defineProps({
    modelValue: {
        type: String,
        default: null
    },
    label: {
        type: String,
        default: ''
    },
    options: {
        type: Array,
        required: true
    },
    uniqueId: {
        type: String,
        required: true
    },
    error: {
        type: String,
        default: null
    }
});

defineEmits(['update:modelValue']);
</script>
