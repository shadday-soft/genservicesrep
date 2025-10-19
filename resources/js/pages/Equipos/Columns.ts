import type { Column } from '@/types';

export const columns: Column[] = [
    { header: 'Nombre del Equipo', field: 'nombre_equipo', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por nombre' },
    { header: 'Tipo', field: 'tipo_equipo', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por tipo' },
    { header: 'Marca Generador', field: 'marca_generador', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por marca' },
    { header: 'Serie Equipo', field: 'serie_equipo', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por serie' },
    { header: 'Sucursal', field: 'sucursal.name', sortable: true, type: 'text', filter: true, filterPlaceholder: 'Buscar por sucursal' },
    { header: 'Acciones', field: 'actions', sortable: false, type: 'actions' },
];

export default columns;
