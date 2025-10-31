import type { Column } from '@/types';

export const columns: Column[] = [

    {
        header: 'Nombre',
        field: 'name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by name',
    },

    {
        header: 'Correo Electrónico',
        field: 'email',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by email',
    }

    ,
    {
        header: 'Rol',
        field: 'role',
        sortable: true,
        type: 'tag',
        filter: true,
        filterPlaceholder: 'Search by role',
        tags: [
            { value: 'Cliente', label: 'Cliente', severity: 'info' },
            { value: 'Tecnico', label: 'Técnico', severity: 'success' },
            { value: 'Administrador', label: 'Administrador', severity: 'warning' },
        ],
    }

];