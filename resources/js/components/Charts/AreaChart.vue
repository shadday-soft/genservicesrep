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
    PointElement,
    Filler
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

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
        backgroundColor: dataset.backgroundColor || `${dataset.color}40`,
        borderColor: dataset.color,
        borderWidth: 3,
        pointBackgroundColor: dataset.color,
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        data: props.data.map(item => Number(item[dataset.key] || 0)),
        tension: 0.4,
        fill: true
    }))
}));

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'index' as const,
        intersect: false,
    },
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
        },
        tooltip: {
            callbacks: {
                label: function(context: any) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += context.parsed.y;
                    return label;
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            stacked: false,
            ticks: {
                stepSize: 1
            }
        },
        x: {
            stacked: false
        }
    }
}));
</script>

<template>
    <Line :data="chartData" :options="chartOptions" />
</template>
