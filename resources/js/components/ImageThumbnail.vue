<template>
    <Image 
        v-if="imageUrl"
        :src="imageUrl.includes('https://reporting.genservices.com.co/storage') ? imageUrl : '/uploads/' + imageUrl" 
        :alt="title"
        preview
        class="w-12 h-12 object-cover rounded border-2 border-gray-300 hover:border-blue-500 cursor-pointer transition-all hover:scale-110 shadow-sm"
    />
</template>

<script setup lang="ts">
import Image from 'primevue/image';

interface Props {
    imageUrl?: string | null;
    title?: string;
}

defineProps<Props>();
</script>
