import type { Column } from '@/types';

export const columns: Column[] = [
    { header: 'Nombre del Equipo', field: 'nombre_equipo', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por nombre' },
    { header: 'Tipo', field: 'tipo_equipo', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por tipo' },
    { header: 'Cliente', field: 'client.enterprise_name', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por cliente' },
    { header: 'Sucursal', field: 'sucursal.name', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por sucursal' },
    { header: 'Detalles', field: 'detalles', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por detalles' },
    
];

export default columns;
