<script setup lang="ts">
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import esLocale from '@fullcalendar/core/locales/es';

interface CalendarEvent {
    id: string;
    title: string;
    start: string;
    backgroundColor: string;
    borderColor: string;
    extendedProps: {
        numero_orden: string;
        cliente: string;
        equipo: string;
        usuario: string;
        estado: string;
        prioridad: string;
    };
}

interface Props {
    events: CalendarEvent[];
}

const props = defineProps<Props>();

const isDialogOpen = ref(false);

const selectedEvent = ref<null | {
    id: string;
    title: string;
    start: string;
    numero_orden: string;
    cliente: string;
    equipo: string;
    usuario: string;
    estado: string;
    prioridad: string;
}>(null);

const calendarOptions = ref({
    plugins: [dayGridPlugin, interactionPlugin, listPlugin],
    initialView: 'dayGridMonth',
    locale: esLocale,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
    },
    buttonText: {
        today: 'Hoy',
        month: 'Mes',
        week: 'Semana',
        day: 'Día',
        list: 'Lista'
    },
    events: props.events,
    height: 'auto',
    eventClick: (info: any) => {
        const event = info.event;
        const props = event.extendedProps;

        selectedEvent.value = {
            id: event.id,
            title: event.title,
            start: event.start?.toISOString() || event.startStr,
            numero_orden: props.numero_orden,
            cliente: props.cliente,
            equipo: props.equipo,
            usuario: props.usuario,
            estado: props.estado,
            prioridad: props.prioridad,
        };

        isDialogOpen.value = true;
    },
    eventDidMount: (info: any) => {
        info.el.style.cursor = 'pointer';
    }
});
</script>

<template>
    <div class="calendar-wrapper">
                <FullCalendar :options="calendarOptions" />

                <Modal v-model:visible="isDialogOpen" :title="selectedEvent?.title || 'Detalle de Solicitud'" width="40rem">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
                        <div class="space-y-2">
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Orden</span>
                                <span class="truncate">#{{ selectedEvent?.numero_orden }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Cliente</span>
                                <span class="truncate">{{ selectedEvent?.cliente }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Equipo</span>
                                <span class="truncate">{{ selectedEvent?.equipo }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Usuario</span>
                                <span class="truncate">{{ selectedEvent?.usuario }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Estado</span>
                                <span class="truncate">{{ selectedEvent?.estado }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Prioridad</span>
                                <span class="truncate">{{ selectedEvent?.prioridad }}</span>
                            </div>
                        </div>

                        <div class="col-span-1 sm:col-span-2">
                            <div class="flex items-start gap-2">
                                <span class="w-28 font-medium text-gray-600">Fecha</span>
                                <span class="truncate">{{ selectedEvent?.start ? new Date(selectedEvent.start).toLocaleString('es-ES') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <template #footer>
                        <div class="w-full flex justify-end gap-3">
                            <!-- Optional: replace with a navigation to the solicitud detail -->
                            <button class="rounded border border-gray-300 px-4 py-2 bg-white text-gray-700 hover:bg-gray-50" @click="isDialogOpen = false">Cerrar</button>
                        </div>
                    </template>
                </Modal>
    </div>
</template>

<style>
.calendar-wrapper {
    --fc-border-color: #e5e7eb;
    --fc-button-bg-color: #842A23;
    --fc-button-border-color: #3037C0;
    --fc-button-hover-bg-color: #3037C0;
    --fc-button-hover-border-color: #842A23;
    --fc-button-active-bg-color: #3037C0;
    --fc-button-active-border-color: #3037C0;
    --fc-today-bg-color: rgba(132, 42, 35, 0.08);
}

:deep(.fc) {
    font-family: inherit;
}

:deep(.fc-button) {
    text-transform: capitalize;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
}

:deep(.fc-button-primary:not(:disabled):active),
:deep(.fc-button-primary:not(:disabled).fc-button-active) {
    background-color: var(--fc-button-active-bg-color);
    border-color: var(--fc-button-active-border-color);
}

:deep(.fc-daygrid-day-number) {
    padding: 0.5rem;
}

:deep(.fc-col-header-cell) {
    background-color: #f9fafb;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 0.75rem 0;
}

:deep(.fc-event) {
    border-radius: 0.25rem;
    padding: 2px 4px;
    margin: 1px 2px;
    font-size: 0.75rem;
}

:deep(.fc-event:hover) {
    opacity: 0.8;
}

:deep(.fc-daygrid-day.fc-day-today) {
    background-color: var(--fc-today-bg-color);
}

:deep(.fc-list-event:hover td) {
    background-color: rgba(220, 38, 38, 0.05);
}

/* Dark mode */
.dark .calendar-wrapper {
    --fc-border-color: #1f2937;
}

.dark :deep(.fc-col-header-cell) {
    background-color: #1f2937;
    color: #f9fafb;
}

.dark :deep(.fc) {
    color: #f9fafb;
}

.dark :deep(.fc-daygrid-day-number) {
    color: #f9fafb;
}

.dark :deep(.fc-scrollgrid) {
    border-color: #374151;
}

.dark :deep(.fc-theme-standard td),
.dark :deep(.fc-theme-standard th) {
    border-color: #374151;
}
</style>
