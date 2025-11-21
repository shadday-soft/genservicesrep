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

interface DataItem {
    mes: string;
    [key: string]: string | number;
}

interface Props {
    data: DataItem[];
    datasets: {
        key: string;
        label: string;
        color: string;
        backgroundColor?: string;
    }[];
    title?: string;
}

const props = defineProps<Props>();

const chartData = computed(() => ({
    labels: props.data.map(item => {
        const [year, month] = item.mes.split('-');
        const date = new Date(parseInt(year), parseInt(month) - 1);
        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
    }),
    datasets: props.datasets.map(dataset => ({
        label: dataset.label,
        backgroundColor: dataset.backgroundColor || `${dataset.color}20`,
        borderColor: dataset.color,
        borderWidth: 3,
        pointBackgroundColor: dataset.color,
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        data: props.data.map(item => Number(item[dataset.key] || 0)),
        tension: 0.4,
        fill: false
    }))
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
