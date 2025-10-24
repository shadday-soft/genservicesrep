<script setup lang="ts">
import { computed } from 'vue';
import { Doughnut } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

interface Props {
    data: { estado?: string; prioridad?: string; tipo?: string; total: number }[];
    title?: string;
    labelKey?: 'estado' | 'prioridad' | 'tipo';
}

const props = withDefaults(defineProps<Props>(), {
    labelKey: 'estado'
});

const colors = [
    '#24C056', // verde
    '#DCBB37', // amarillo
    '#842A23', // rojo oscuro
    '#3037C0', // azul
    '#7f1d1d', // fallback rojo
    '#374151', // fallback gris
];

const chartData = computed(() => ({
    labels: props.data.map(item => item[props.labelKey] || 'Sin datos'),
    datasets: [
        {
            backgroundColor: colors.slice(0, props.data.length),
            borderWidth: 2,
            borderColor: '#ffffff',
            data: props.data.map(item => item.total)
        }
    ]
}));

const chartOptions = computed(() => ({ 
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'bottom' as const,
        },
        title: {
            display: !!props.title,
            text: props.title,
            font: {
                size: 16,
                weight: 'bold' as const
            }
        }
    }
}));
</script>

<template>
    <Doughnut :data="chartData" :options="chartOptions" />
</template>
