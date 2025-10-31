<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import clients from '@/routes/clients';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Building, LayoutGrid, Users, SquareUser, CircuitBoard, UserCog, List, Calendar, AlertCircle } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import sucursals from '@/routes/sucursals';
import equipos from '@/routes/equipos';
import solicituds from '@/routes/solicituds';
import actividads from '@/routes/actividads';
import users from '@/routes/users';
import { validateRole } from '@/composables/useCommonUtilities';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        show: validateRole('Administrador'),
    },
    {
        title: 'Actividades',
        href: actividads.index(),
        icon: LayoutGrid,
        show: validateRole('Administrador'),
    },
    {
        title: 'Tecnicos',
        href: users.index({ query: { role: 'Tecnico' } }),
        icon: UserCog,
        show: validateRole('Administrador'),
    },
    {
        title: 'Clientes',
        href: clients.index(),
        icon: SquareUser,
        show: validateRole('Administrador'),
    },
    {
        title: 'Sucursales',
        href: sucursals.index(),
        icon: Building,
        show: validateRole('Administrador'),
    },
    {
        title: 'Equipos',
        href: equipos.index(),
        icon: CircuitBoard,
        show: validateRole('Tecnico') || validateRole('Administrador'),    
    },
    {
        title: 'Solicitudes',
        href: solicituds.index(),
        icon: BookOpen,
        items: [
            {
                title: 'Preventivo',
                href: solicituds.index({
                    query: { tipo: 'Mantenimiento Preventivo' },
                }),
                icon: Calendar,
            },
            {
                title: 'Por Demanda',
                href: solicituds.index({
                    query: { tipo: 'Mantenimiento Correctivo' },
                }),
                icon: AlertCircle,
            },
            {
                title: 'Todas las Solicitudes',
                href: solicituds.index(),
                icon: List,
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Usuarios',
        href: 'users',
        icon: Users,
    },

];
</script>

<template>
    <Sidebar collapsible="icon" variant="floating">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
