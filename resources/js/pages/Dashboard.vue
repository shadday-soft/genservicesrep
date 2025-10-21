<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import BarChart from '@/components/Charts/BarChart.vue';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import SolicitudCalendar from '@/components/Calendar/SolicitudCalendar.vue';

interface Props {
    stats: {
        totalSolicitudes: number;
        totalClientes: number;
        totalEquipos: number;
        totalUsuarios: number;
    };
    charts: {
        solicitudesPorUsuario: { usuario: string; total: number }[];
        solicitudesPorEstado: { estado: string; total: number }[];
        solicitudesPorPrioridad: { prioridad: string; total: number }[];
        solicitudesPorTipoEquipo: { tipo: string; total: number }[];
        solicitudesPorMes: { mes: string; total: number }[];
    };
    ultimasSolicitudes: any[];
    solicitudesProgramadas: {
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
    }[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const getEstadoColor = (estado: string) => {
    const colors: Record<string, string> = {
        'Nueva': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'Proceso': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'Revisión': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        'Finalizada': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'Anulada': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'Programada': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
    };
    return colors[estado] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Solicitudes -->
                <div class="rounded-xl bg-gradient-to-br from-red-600 to-red-700 p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium opacity-90">Total Solicitudes</p>
                            <p class="mt-2 text-3xl font-bold">{{ stats.totalSolicitudes }}</p>
                        </div>
                        <div class="rounded-full bg-white/20 p-3">
                            <i class="pi pi-file text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Clientes -->
                <div class="rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium opacity-90">Total Clientes</p>
                            <p class="mt-2 text-3xl font-bold">{{ stats.totalClientes }}</p>
                        </div>
                        <div class="rounded-full bg-white/20 p-3">
                            <i class="pi pi-users text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Equipos -->
                <div class="rounded-xl bg-gradient-to-br from-red-500 to-red-600 p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium opacity-90">Total Equipos</p>
                            <p class="mt-2 text-3xl font-bold">{{ stats.totalEquipos }}</p>
                        </div>
                        <div class="rounded-full bg-white/20 p-3">
                            <i class="pi pi-box text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Usuarios -->
                <div class="rounded-xl bg-gradient-to-br from-slate-700 to-slate-800 p-4 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium opacity-90">Total Usuarios</p>
                            <p class="mt-2 text-3xl font-bold">{{ stats.totalUsuarios }}</p>
                        </div>
                        <div class="rounded-full bg-white/20 p-3">
                            <i class="pi pi-user text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfica principal: Solicitudes por Usuario -->
            <div class="rounded-xl bg-white p-4 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">
                    Solicitudes Asignadas por Usuario
                </h2>
                <div class="h-[400px]">
                    <BarChart 
                        :data="charts.solicitudesPorUsuario" 
                        title="Top 10 Usuarios con más Solicitudes"
                    />
                </div>
            </div>

            <!-- Grid de gráficas -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <!-- Solicitudes por Estado -->
                <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                    <h2 class="mb-4 text-lg font-bold text-gray-800 dark:text-white">
                        Distribución por Estado
                    </h2>
                    <div class="h-[300px]">
                        <DoughnutChart 
                            :data="charts.solicitudesPorEstado" 
                            labelKey="estado"
                        />
                    </div>
                </div>

                <!-- Solicitudes por Prioridad -->
                <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                    <h2 class="mb-4 text-lg font-bold text-gray-800 dark:text-white">
                        Distribución por Prioridad
                    </h2>
                    <div class="h-[300px]">
                        <DoughnutChart 
                            :data="charts.solicitudesPorPrioridad" 
                            labelKey="prioridad"
                        />
                    </div>
                </div>
                 <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">
                    Solicitudes por Tipo de Equipo
                </h2>
                <div class="h-[300px]">
                    <BarChart 
                        :data="charts.solicitudesPorTipoEquipo.map(item => ({ usuario: item.tipo, total: item.total }))"
                    />
                </div>
            </div>
            </div>

            <!-- Calendario de Solicitudes Programadas -->
            <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">
                    Calendario de Solicitudes Programadas
                </h2>
                <SolicitudCalendar :events="solicitudesProgramadas" />
            </div>

            <!-- Últimas Solicitudes -->
            <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">
                    Últimas Solicitudes
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Orden
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Equipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Usuario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Fecha
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            <tr v-for="solicitud in ultimasSolicitudes" :key="solicitud.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    #{{ solicitud.numero_orden }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ solicitud.client?.enterprise_name || 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ solicitud.equipo?.nombre_equipo || 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ solicitud.user?.name || 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span :class="getEstadoColor(solicitud.estado)" 
                                          class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                        {{ solicitud.estado }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ new Date(solicitud.created_at).toLocaleDateString('es-ES') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
