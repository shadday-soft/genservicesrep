<script setup lang="ts">
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

interface Props {
    data: { mes: string; total: number }[];
    title?: string;
}

const props = defineProps<Props>();

const chartData = computed(() => ({
    labels: props.data.map(item => {
        const [year, month] = item.mes.split('-');
        const date = new Date(parseInt(year), parseInt(month) - 1);
        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
    }),
    datasets: [
        {
            label: 'Solicitudes',
            backgroundColor: 'rgba(132, 42, 35, 0.08)',
            borderColor: '#842A23',
            borderWidth: 3,
            pointBackgroundColor: '#24C056',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8,
            data: props.data.map(item => item.total),
            tension: 0.4,
            fill: true
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
    <Line :data="chartData" :options="chartOptions" />
</template>
