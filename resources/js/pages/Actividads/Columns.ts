import type { Column } from '@/types';

export const columns: Column[] = [
    { header: 'Nombre', field: 'nombre', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por nombre' },
    { header: 'Activo', field: 'active', sortable: true, type: 'boolean', filter: true, filterPlaceholder: 'Filtrar por estado' },
];

export default columns;
