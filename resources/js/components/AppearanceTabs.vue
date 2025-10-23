<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { Button } from 'primevue';
import SpeedDial from 'primevue/speeddial';

interface Props {
    class?: string;
}

const { class: containerClass = '' } = defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { icon: 'pi pi-sun', label: 'Light', command: () => updateAppearance('light') },
    { icon: 'pi pi-moon', label: 'Dark', command: () => updateAppearance('dark') },
    { icon: 'pi pi-desktop', label: 'System', command: () => updateAppearance('system') },
] as const;
</script>

<template>
    <div style="position: fixed; right: 2rem; bottom: 2rem; z-index: 90;">
        <SpeedDial :model="tabs" direction="up">
            <template #button="{ toggleCallback }">
                <Button size="small" rounded  @click="toggleCallback" :icon="`pi pi-${appearance == 'light' ? 'sun':appearance == 'dark' ? 'moon':'desktop'}`" />
            </template>
        </SpeedDial>
    </div>
</template>
