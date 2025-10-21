<script setup lang="ts">
import { ref } from 'vue';
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
        
        alert(`
Orden: #${props.numero_orden}
Cliente: ${props.cliente}
Equipo: ${props.equipo}
Usuario: ${props.usuario}
Estado: ${props.estado}
Prioridad: ${props.prioridad}
Fecha: ${new Date(event.start).toLocaleDateString('es-ES')}
        `.trim());
    },
    eventDidMount: (info: any) => {
        info.el.style.cursor = 'pointer';
    }
});
</script>

<template>
    <div class="calendar-wrapper">
        <FullCalendar :options="calendarOptions" />
    </div>
</template>

<style>
.calendar-wrapper {
    --fc-border-color: #e5e7eb;
    --fc-button-bg-color: #dc2626;
    --fc-button-border-color: #991b1b;
    --fc-button-hover-bg-color: #991b1b;
    --fc-button-hover-border-color: #7f1d1d;
    --fc-button-active-bg-color: #7f1d1d;
    --fc-button-active-border-color: #7f1d1d;
    --fc-today-bg-color: rgba(220, 38, 38, 0.1);
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
    --fc-border-color: #374151;
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
