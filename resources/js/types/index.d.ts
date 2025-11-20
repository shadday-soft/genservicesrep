import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';
import { Client } from './client';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    items?: NavItem[];
    show?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    role: 'Administrador' | 'Tecnico' | 'Cliente';
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Column {
    field: string;
    header: string;
    sortable?: boolean;
    type?: string;
    filter?: boolean;
    tags?: { label: string; value: string; severity: string }[];
    filterPlaceholder?: string;
    filterMatchMode?: string;
    filterValue?: string | number | boolean;
}

export interface Sucursal{
    id: string;
    client_id: string;
    name: string;
    client?: Client;
    address: string;
    phone_number: string;
    contact_name: string;
    image: string;
    latitude?: number | null;
    longitude?: number | null;
    email: string;
    latitude?: number | null;
    longitude?: number | null;
}


export interface Equipo {
    id: string;
    client_id?: string | null;
    sucursal_id?: string | null;
    nombre_equipo: string;
    detalles?: string | null;
    tipo_equipo: string;

    // Planta Eléctrica
    potencia?: string | null;
    modelo_equipo?: string | null;
    modelo_motor?: string | null;
    tension_operacion?: string | null;
    serie_equipo?: string | null;
    serie_motor?: string | null;
    marca_generador?: string | null;
    horometro?: string | null;
    marca_motor?: string | null;

    // Tablero
    tablero_tipo?: string | null;
    tablero_tension_operacion?: string | null;
    tablero_tipo_aplicacion?: string | null;
    tablero_fabricante?: string | null;
    tablero_corriente_nominal?: string | null;
    tablero_elemento_maniobra?: string | null;
    tablero_controlador?: string | null;

    // Insumos
    filtro_aire_cantidad?: string | null;
    filtro_aire_referencia?: string | null;
    filtro_aceite_cantidad?: string | null;
    filtro_aceite_referencia?: string | null;
    filtro_combustible_cantidad?: string | null;
    filtro_combustible_referencia?: string | null;
    filtro_separador_cantidad?: string | null;
    filtro_separador_referencia?: string | null;
    filtro_agua_cantidad?: string | null;
    filtro_agua_referencia?: string | null;
    filtro_aceite_2_cantidad?: string | null;
    filtro_aceite_2_referencia?: string | null;
    refrigerante_cantidad?: string | null;
    refrigerante_referencia?: string | null;
    fecha_primer_mantenimiento?: string | null;
    // Relaciones opcionales para UI convenience
    client?: Client;
    sucursal?: Sucursal;
}

export interface Actividad {
    id: string;
    nombre: string;
    active: boolean;
    created_at?: string;
    updated_at?: string;
}


export interface Solicitud {
    id: string;
    client_id: string | null;
    sucursal_id: string;
    equipo_id: string;
    user_id: string;
    numero_orden?: string | null;
    prioridad: 'Normal' | 'Intermedio' | 'Urgente';
    detalles?: string | null;
    estado: 'Nueva' | 'Proceso' | 'Revisión' | 'Finalizada' | 'Anulada' | 'Programada';
    razon_cancelacion?: string | null;
    mantenimiento_id?: string | null;
    fecha_mantenimiento?: string | null;
    telefono?: string | null;
    mail?: string | null;
    ubicacion?: string | null;
    quien_solicita?: string | null;
    fecha_programada?: string | null;
    informe_generado?: boolean | null;
    fecha_informe?: string | null;
    orden_trabajo?: string | null;
    created_at: string;
    updated_at: string;

    client?: Client;
    sucursal?: Sucursal;
    equipo?: Equipo;
    user?: User;
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface Tecnico {
    id: string;
    user_id: string;
    foto: string | null;
    identificacion: string;
    correo: string;
    nombre_completo: string;
    persona_contacto: string | null;
    telefono_contacto: string | null;
    direccion_contacto: string | null;
    tipo_sangre: 'A+' | 'A-' | 'B+' | 'B-' | 'AB+' | 'AB-' | 'O+' | 'O-' | null;
    eps: string | null;
    fecha_nacimiento: string | null;
    fecha_inicio_contrato: string;
    fecha_fin_contrato: string | null;
    tipo_contrato: 'Indefinido' | 'Fijo' | 'Obra o labor' | 'Prestación de servicios';
    activo: boolean;
    firma: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
}

export type BreadcrumbItemType = BreadcrumbItem;
