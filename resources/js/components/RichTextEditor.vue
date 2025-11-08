<script setup lang="ts">
import { ref, watch } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

interface Props {
    modelValue?: string | null;
    label?: string;
    error?: string;
    disabled?: boolean;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    label: '',
    disabled: false,
    placeholder: 'Escribe aquí...',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const content = ref(props.modelValue || '');

watch(() => props.modelValue, (newValue) => {
    if (newValue !== content.value) {
        content.value = newValue || '';
    }
});

const updateContent = (newContent: string) => {
    content.value = newContent;
    emit('update:modelValue', newContent);
};

const toolbarOptions = [
    [{ 'header': [1, 2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'align': [] }],
    ['blockquote', 'code-block'],
    ['link'],
    ['clean']
];
</script>

<template>
    <div class="flex flex-col gap-y-1">
        <label v-if="label" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
            {{ label }}
        </label>
        <QuillEditor
            v-model:content="content"
            :toolbar="toolbarOptions"
            :placeholder="placeholder"
            :disabled="disabled"
            theme="snow"
            contentType="html"
            @update:content="updateContent"
        />
        <div v-if="error" class="text-red-600 text-sm mt-1">
            {{ error }}
        </div>
    </div>
</template>

<style scoped>
/* Estilos personalizados para Quill */
:deep(.ql-container) {
    min-height: 200px;
    font-size: 14px;
}

:deep(.ql-editor) {
    min-height: 200px;
}

:deep(.ql-toolbar) {
    background-color: #f9fafb;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

:deep(.ql-container) {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

:deep(.ql-editor.ql-blank::before) {
    color: #9ca3af;
    font-style: italic;
}

/* Modo oscuro */
.dark :deep(.ql-toolbar) {
    background-color: #374151;
    border-color: #4b5563;
}

.dark :deep(.ql-container) {
    background-color: #1f2937;
    border-color: #4b5563;
    color: #e5e7eb;
}

.dark :deep(.ql-editor.ql-blank::before) {
    color: #6b7280;
}

.dark :deep(.ql-stroke) {
    stroke: #9ca3af;
}

.dark :deep(.ql-fill) {
    fill: #9ca3af;
}

.dark :deep(.ql-picker-label) {
    color: #9ca3af;
}
</style>
