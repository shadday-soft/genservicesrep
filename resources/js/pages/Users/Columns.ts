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


];