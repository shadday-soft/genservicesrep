import type { Column } from '@/types';

export const columns: Column[] = [
    {
        header: 'Solicitado por',
        field: 'client.enterprise_name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por empresa',
    },
    {
        header: 'Número de Orden',
        field: 'numero_orden',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por número de orden',
    },
    
    {
        header: 'Sucursal',
        field: 'sucursal.name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por sucursal',
    },
    {
        header: 'Equipo',
        field: 'equipo.nombre_equipo',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por equipo',
    },
    {
        header: 'Prioridad',
        field: 'prioridad',
        sortable: true,
        type: 'tag',
        filter: true,
        filterPlaceholder: 'Buscar por prioridad',
        tags: [
            {
                label: 'Normal',
                value: 'Normal',
                severity: 'success',
            },
            {
                label: 'Intermedio',
                value: 'Intermedio',
                severity: 'warn',
            },
            {
                label: 'Urgente',
                value: 'Urgente',
                severity: 'danger',
            },
        ]
    },
    {
        header: 'Estado',
        field: 'estado',
        sortable: true,
        type: 'tag',
        filter: true,
        tags: [
            {
                label: 'Nueva',
                value: 'Nueva',
                severity: 'info',
            },
            {
                label: 'Revisión',
                value: 'Revisión',
                severity: 'warn',
            }
        ],
        filterPlaceholder: 'Buscar por estado',
    },
    {
        header: 'Fecha Programada',
        field: 'fecha_programada',
        sortable: true,
        type: 'dateTime',
        filter: true,
        filterPlaceholder: 'Buscar por fecha',
    },
    {
        header: 'Técnico Asignado',
        field: 'user.name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por técnico',
    },
]
