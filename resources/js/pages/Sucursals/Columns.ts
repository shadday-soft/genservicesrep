import type { Column } from '@/types';

export const columns: Column[] = [
    {
        header: 'Nombre de la Sucursal',
        field: 'name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by name',
    },
    {
        header: 'Nombre del Contacto',
        field: 'contact_name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by contact name',
    },

    {
        header: 'Numero de Contacto',
        field: 'phone_number',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by phone number',
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
        header: 'Dirección',
        field: 'address',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by address',
    },
    {
        header: 'Ciudad',
        field: 'ciudad',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by city',
    },
    {
        header: 'Cliente',
        field: 'client.enterprise_name',
        sortable: true,
        type: 'text',
        filter: true,
        filterPlaceholder: 'Search by client',
    },
    {
        header: 'Imagen',
        field: 'image',
        sortable: false,
        type: 'image',
        filter: false,
        filterPlaceholder: '',
    }



]