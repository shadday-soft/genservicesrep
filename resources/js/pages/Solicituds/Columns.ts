import type { Column } from '@/types';

export const columns: Column[] = [
    {
        header: '',
        field: 'select',
        sortable: false,
        type: 'slot',
        filter: false
    },
    {
        header: 'Numero de solicitud',
        field: 'numero_orden',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por ID',
    },
    {
        header: 'Solicitado por',
        field: 'client.enterprise_name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por empresa',
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
        header: 'Fecha de Solicitud',
        field: 'created_at',
        sortable: true,
        type: 'dateTime',
        filter: true,
        filterPlaceholder: 'Buscar por fecha',
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
        header: 'Tipo de Mantenimiento',
        field: 'tipo_mantenimiento_display',
        sortable: true,
        type: 'tag',
        filter: true,
        filterPlaceholder: 'Buscar por tipo',
        tags: [
            {
                label: 'Preventivo',
                value: 'Mantenimiento Preventivo',
                severity: 'info',
            },
            {
                label: 'Correctivo',
                value: 'Mantenimiento Correctivo',
                severity: 'warn',
            },
        ]
    },
    {
        header: 'Estado',
        field: 'estado',
        sortable: true,
        type: 'tag',
        filter: true,
        filterPlaceholder: 'Buscar por estado',
        tags: [
            {
                label: 'Nueva',
                value: 'Nueva',
                severity: 'warn',
            },
            {
                label: 'Proceso',
                value: 'Proceso',
                severity: 'info',
            },
            {
                label: 'Finalizada',
                value: 'Finalizada',
                severity: 'success',
            },
        ]},
    {
        header: 'Técnico Asignado',
        field: 'user.name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por técnico',
    },

    {
        header: 'Firma Cliente',
        field: 'firma_cliente',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Buscar por firma',
    },
    {
        header: 'Detalles',
        field: 'detalles',
        sortable: true,
        type: 'slot',
        filter: true,
        filterPlaceholder: 'Buscar por detalles',
    }
]
