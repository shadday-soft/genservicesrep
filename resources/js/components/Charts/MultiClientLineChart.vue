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
    total: number;
}

interface Props {
    data: Record<string, DataItem[]>;
    title?: string;
}

const props = defineProps<Props>();

const colors = [
    '#842A23', '#3037C0', '#24C056', '#DCBB37', '#9333ea',
    '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#8b5cf6'
];

const chartData = computed(() => {
    const allMonths = new Set<string>();
    Object.values(props.data).forEach(clientData => {
        clientData.forEach(item => allMonths.add(item.mes));
    });
    
    const sortedMonths = Array.from(allMonths).sort();
    
    return {
        labels: sortedMonths.map(mes => {
            const [year, month] = mes.split('-');
            const date = new Date(parseInt(year), parseInt(month) - 1);
            return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        }),
        datasets: Object.entries(props.data).map(([cliente, clienteData], index) => {
            const dataMap = new Map(clienteData.map(item => [item.mes, item.total]));
            
            return {
                label: cliente,
                backgroundColor: `${colors[index % colors.length]}20`,
                borderColor: colors[index % colors.length],
                borderWidth: 2,
                pointBackgroundColor: colors[index % colors.length],
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                data: sortedMonths.map(mes => dataMap.get(mes) || 0),
                tension: 0.4,
                fill: false
            };
        })
    };
});

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
