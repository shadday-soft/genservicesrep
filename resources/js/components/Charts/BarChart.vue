<script setup lang="ts">
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

interface Props {
    data: { usuario: string; total: number }[];
    title?: string;
}

const props = defineProps<Props>();

const chartData = computed(() => ({
    labels: props.data.map(item => item.usuario),
    datasets: [
        {
            label: 'Solicitudes asignadas',
            backgroundColor: '#842A23',
            borderColor: '#3037C0',
            borderWidth: 2,
            borderRadius: 8,
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
            position: 'top' as const,
        },
        title: {
            display: !!props.title,
            text: props.title,
            font: {
                size: 16,
                weight: 'bold' as const
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1
            }
        }
    }
}));
</script>

<template>
    <Bar :data="chartData" :options="chartOptions" />
</template>
