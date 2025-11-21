<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import BarChart from '@/components/Charts/BarChart.vue';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import MultiLineChart from '@/components/Charts/MultiLineChart.vue';
import MultiClientLineChart from '@/components/Charts/MultiClientLineChart.vue';
import HorizontalBarChart from '@/components/Charts/HorizontalBarChart.vue';
import AreaChart from '@/components/Charts/AreaChart.vue';
import SolicitudCalendar from '@/components/Calendar/SolicitudCalendar.vue';
import { useChartDownload } from '@/composables/useChartDownload';

const { downloadChartAsPng } = useChartDownload();

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
        mantenimientosProgramadosVsEjecutados: { mes: string; programados: number; ejecutados: number }[];
        mantenimientosMesActual: { programados: number; ejecutados: number };
        mantenimientosPorTipoMesActual: { tipo: string; total: number }[];
        emergenciasPorClienteMes: { usuario: string; total: number }[];
        solicitudesPorTecnicoMensual: { usuario: string; total: number }[];
        ordenesAbiertasVsFinalizadas: { mes: string; abiertas: number; finalizadas: number }[];
        correctivosPorTipo: { actividad: string; total: number }[];
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
                <div class="rounded-xl bg-gradient-to-br from-[#842A23] to-[#3037C0] p-4 text-white shadow-lg">
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
                <div class="rounded-xl bg-gradient-to-br from-[#24C056] to-[#DCBB37] p-4 text-white shadow-lg">
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
                <div class="rounded-xl bg-gradient-to-br from-[#DCBB37] to-[#842A23] p-4 text-white shadow-lg">
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
                <div class="rounded-xl bg-gradient-to-br from-[#3037C0] to-[#842A23] p-4 text-white shadow-lg">
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

            <!-- Tarjetas de Mantenimientos del Mes Actual -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Porcentaje de Mantenimientos Ejecutados -->
                <div class="rounded-xl bg-gradient-to-br from-[#24C056] to-[#3037C0] p-8 text-white shadow-lg">
                    <div class="text-center">
                        <h3 class="mb-2 text-lg font-medium opacity-90">
                            Mantenimientos Ejecutados - Mes Actual
                        </h3>
                        <div class="my-6">
                            <div class="text-7xl font-bold">
                                {{ charts.mantenimientosMesActual.programados > 0 
                                    ? Math.round((charts.mantenimientosMesActual.ejecutados / charts.mantenimientosMesActual.programados) * 100) 
                                    : 0 }}%
                            </div>
                            <div class="mt-4 text-2xl font-semibold">
                                {{ charts.mantenimientosMesActual.ejecutados }} / {{ charts.mantenimientosMesActual.programados }}
                            </div>
                        </div>
                        <div class="mt-6 flex justify-center gap-8">
                            <div class="text-center">
                                <div class="mb-1 text-3xl font-bold">
                                    {{ charts.mantenimientosMesActual.ejecutados }}
                                </div>
                                <div class="text-sm opacity-90">Completados</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-1 text-3xl font-bold">
                                    {{ Math.max(0, charts.mantenimientosMesActual.programados - charts.mantenimientosMesActual.ejecutados) }}
                                </div>
                                <div class="text-sm opacity-90">Pendientes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Dona de Mantenimientos por Tipo -->
                <div class="rounded-xl bg-gradient-to-br from-[#DCBB37] to-[#842A23] p-6 text-white shadow-lg">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-center text-xl font-bold">
                            Mantenimientos por Tipo - Mes Actual
                        </h2>
                        <button
                            @click="downloadChartAsPng('chart-mantenimientos-tipo', 'mantenimientos-por-tipo-mes-actual')"
                            class="rounded-lg bg-white/20 p-2 transition hover:bg-white/30"
                            title="Descargar como PNG"
                        >
                            <i class="pi pi-download text-sm"></i>
                        </button>
                    </div>
                    <div id="chart-mantenimientos-tipo" class="h-[280px]">
                        <DoughnutChart 
                            :data="charts.mantenimientosPorTipoMesActual" 
                            labelKey="tipo"
                            :darkBackground="true"
                        />
                    </div>
                </div>
            </div>

            <!-- Gráfica principal: Solicitudes por Usuario -->
            <div class="rounded-xl bg-white p-4 shadow-lg dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        Solicitudes Asignadas por Usuario
                    </h2>
                    <button
                        @click="downloadChartAsPng('chart-solicitudes-usuario', 'solicitudes-por-usuario')"
                        class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        title="Descargar como PNG"
                    >
                        <i class="pi pi-download"></i>
                    </button>
                </div>
                <div id="chart-solicitudes-usuario" class="h-[400px]">
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
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                            Distribución por Estado
                        </h2>
                        <button
                            @click="downloadChartAsPng('chart-estado', 'solicitudes-por-estado')"
                            class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                            title="Descargar como PNG"
                        >
                            <i class="pi pi-download text-sm"></i>
                        </button>
                    </div>
                    <div id="chart-estado" class="h-[300px]">
                        <DoughnutChart 
                            :data="charts.solicitudesPorEstado" 
                            labelKey="estado"
                        />
                    </div>
                </div>

                <!-- Solicitudes por Prioridad -->
                <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                            Distribución por Prioridad
                        </h2>
                        <button
                            @click="downloadChartAsPng('chart-prioridad', 'solicitudes-por-prioridad')"
                            class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                            title="Descargar como PNG"
                        >
                            <i class="pi pi-download text-sm"></i>
                        </button>
                    </div>
                    <div id="chart-prioridad" class="h-[300px]">
                        <DoughnutChart 
                            :data="charts.solicitudesPorPrioridad" 
                            labelKey="prioridad"
                        />
                    </div>
                </div>
                 <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                            Solicitudes por Tipo de Equipo
                        </h2>
                        <button
                            @click="downloadChartAsPng('chart-tipo-equipo', 'solicitudes-por-tipo-equipo')"
                            class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                            title="Descargar como PNG"
                        >
                            <i class="pi pi-download text-sm"></i>
                        </button>
                    </div>
                    <div id="chart-tipo-equipo" class="h-[300px]">
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

           

            <!-- Órdenes Creadas vs Finalizadas -->
            <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        Órdenes Creadas vs Finalizadas por Mes
                    </h2>
                    <button
                        @click="downloadChartAsPng('chart-ordenes-creadas', 'ordenes-creadas-vs-finalizadas')"
                        class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        title="Descargar como PNG"
                    >
                        <i class="pi pi-download"></i>
                    </button>
                </div>
                <div id="chart-ordenes-creadas" class="h-[400px]">
                    <AreaChart 
                        :data="charts.ordenesAbiertasVsFinalizadas"
                        :datasets="[
                            { key: 'abiertas', label: 'Total Abiertas', color: '#3037C0', backgroundColor: 'rgba(48, 55, 192, 0.2)' },
                            { key: 'finalizadas', label: 'Finalizadas', color: '#24C056', backgroundColor: 'rgba(36, 192, 86, 0.3)' }
                        ]"
                    />
                </div>
            </div>

            <!-- Emergencias por Cliente -->
            <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        Emergencias Atendidas por Cliente (Top 10)
                    </h2>
                    <button
                        @click="downloadChartAsPng('chart-emergencias-cliente', 'emergencias-por-cliente')"
                        class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        title="Descargar como PNG"
                    >
                        <i class="pi pi-download"></i>
                    </button>
                </div>
                <div id="chart-emergencias-cliente" class="h-[450px]">
                    <HorizontalBarChart 
                        :data="charts.emergenciasPorClienteMes"
                        title="Clientes con Más Emergencias (Últimos 6 Meses)"
                        :reverse="true"
                    />
                </div>
            </div>

            <!-- Solicitudes por Técnico Mensual -->
            <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        Solicitudes Asignadas por Técnico (Mes Actual)
                    </h2>
                    <button
                        @click="downloadChartAsPng('chart-solicitudes-tecnico', 'solicitudes-por-tecnico-mes-actual')"
                        class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        title="Descargar como PNG"
                    >
                        <i class="pi pi-download"></i>
                    </button>
                </div>
                <div id="chart-solicitudes-tecnico" class="h-[500px]">
                    <HorizontalBarChart 
                        :data="charts.solicitudesPorTecnicoMensual"
                        title="Top 15 Técnicos con Más Solicitudes del Mes"
                    />
                </div>
            </div>

            <!-- Correctivos por Tipo del Mes Actual -->
            <div class="rounded-xl bg-white p-6 shadow-lg dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        Consolidado de Mantenimientos Correctivos del Mes Actual
                    </h2>
                    <button
                        @click="downloadChartAsPng('chart-correctivos-tipo', 'correctivos-por-tipo-mes-actual')"
                        class="rounded-lg bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        title="Descargar como PNG"
                    >
                        <i class="pi pi-download"></i>
                    </button>
                </div>
                <div id="chart-correctivos-tipo" class="h-[400px]">
                    <BarChart 
                        :data="charts.correctivosPorTipo.map(item => ({ usuario: item.actividad, total: item.total }))"
                        title="Tipos de Correctivos Realizados"
                    />
                </div>
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
