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
    reverse?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    reverse: false
});

const colors = [
    '#842A23', '#3037C0', '#24C056', '#DCBB37', '#9333ea',
    '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#8b5cf6'
];

const chartData = computed(() => ({
    labels: props.data.map(item => item.usuario),
    datasets: [
        {
            label: 'Total',
            backgroundColor: props.data.map((_, index) => colors[index % colors.length]),
            borderColor: props.data.map((_, index) => colors[index % colors.length]),
            borderWidth: 2,
            data: props.data.map(item => item.total),
        }
    ]
}));

const chartOptions = computed(() => ({
    indexAxis: 'y' as const,
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
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
        x: {
            beginAtZero: true,
            reverse: props.reverse,
            position: props.reverse ? 'top' as const : 'bottom' as const,
            ticks: {
                stepSize: 1
            }
        },
        y: {
            position: props.reverse ? 'right' as const : 'left' as const,
            ticks: {
                autoSkip: false
            }
        }
    }
}));
</script>

<template>
    <Bar :data="chartData" :options="chartOptions" />
</template>
