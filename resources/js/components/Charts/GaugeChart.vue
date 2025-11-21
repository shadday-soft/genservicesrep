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
    programados: number;
    ejecutados: number;
    title?: string;
}

const props = defineProps<Props>();

const porcentaje = computed(() => {
    if (props.programados === 0) return 0;
    return Math.round((props.ejecutados / props.programados) * 100);
});

const chartData = computed(() => ({
    labels: ['Ejecutados', 'Pendientes'],
    datasets: [
        {
            data: [props.ejecutados, Math.max(0, props.programados - props.ejecutados)],
            backgroundColor: [
                '#24C056',
                '#E5E7EB'
            ],
            borderWidth: 0,
            circumference: 180,
            rotation: 270,
        }
    ]
}));

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    cutout: '75%',
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
        },
        tooltip: {
            callbacks: {
                label: function(context: any) {
                    const label = context.label || '';
                    const value = context.parsed;
                    return `${label}: ${value}`;
                }
            }
        }
    }
}));

const gaugeNeedlePlugin = {
    id: 'gaugeNeedle',
    afterDatasetDraw(chart: any) {
        const { ctx, chartArea: { width, height } } = chart;
        ctx.save();
        
        const needleValue = props.ejecutados;
        const dataTotal = props.programados || 1;
        const angle = Math.PI + (needleValue / dataTotal * Math.PI);
        
        const cx = width / 2;
        const cy = chart._metasets[0].data[0].y;
        const radius = height - cy - 30;
        
        // Needle shadow
        ctx.translate(cx, cy);
        ctx.rotate(angle);
        ctx.beginPath();
        ctx.moveTo(0, -5);
        ctx.lineTo(radius, 0);
        ctx.lineTo(0, 5);
        ctx.closePath();
        ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
        ctx.fill();
        ctx.restore();
        
        // Needle
        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(angle);
        ctx.beginPath();
        ctx.moveTo(0, -4);
        ctx.lineTo(radius, 0);
        ctx.lineTo(0, 4);
        ctx.closePath();
        ctx.fillStyle = '#842A23';
        ctx.fill();
        
        // Needle outline
        ctx.strokeStyle = '#fff';
        ctx.lineWidth = 1;
        ctx.stroke();
        ctx.restore();
        
        // Needle center circle
        ctx.save();
        ctx.beginPath();
        ctx.arc(cx, cy, 10, 0, Math.PI * 2);
        ctx.fillStyle = '#842A23';
        ctx.fill();
        ctx.strokeStyle = '#fff';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        // Inner circle
        ctx.beginPath();
        ctx.arc(cx, cy, 5, 0, Math.PI * 2);
        ctx.fillStyle = '#fff';
        ctx.fill();
        ctx.restore();
    }
};
</script>

<template>
    <div class="relative">
        <div class="h-full">
            <Doughnut :data="chartData" :options="chartOptions" :plugins="[gaugeNeedlePlugin]" />
        </div>
        
        <!-- Porcentaje en el centro -->
        <div class="absolute inset-0 flex items-center justify-center" style="margin-top: 20%;">
            <div class="text-center">
                <div class="text-5xl font-bold text-gray-800 dark:text-white">
                    {{ porcentaje }}%
                </div>
                <div class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    {{ ejecutados }} / {{ programados }}
                </div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                    Mantenimientos Ejecutados
                </div>
            </div>
        </div>
        
        <!-- Leyenda -->
        <div class="mt-4 flex items-center justify-center gap-6">
            <div class="flex items-center gap-2">
                <div class="h-3 w-3 rounded-full bg-[#24C056]"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Ejecutados: {{ ejecutados }}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-3 w-3 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Pendientes: {{ Math.max(0, programados - ejecutados) }}</span>
            </div>
        </div>
    </div>
</template>
