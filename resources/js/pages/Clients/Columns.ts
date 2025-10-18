import type { Column } from '@/types';

export const columns: Column[] = [
    {
        header: 'Empresa',
        field: 'enterprise_name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by name',
    },
    {
        header: 'NIT',
        field: 'nit',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by NIT',
    },

    {
        header: 'Contacto',
        field: 'contact_name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by contact name',
    },
    {
        header: 'Correo Electrónico',
        field: 'email',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by email',
    },
    {
        header: 'Teléfono',
        field: 'phone_number',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by phone number',
    },



]