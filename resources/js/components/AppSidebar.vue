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
import tecnicos  from '@/routes/tecnicos';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Building, LayoutGrid, Users, SquareUser, CircuitBoard, UserCog, List, Calendar, AlertCircle, ListFilterPlus } from 'lucide-vue-next';
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
        icon: ListFilterPlus,
        show: validateRole('Administrador'),
    },
    {
        title: 'Tecnicos',
        href: tecnicos.index(),
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
        show: validateRole('Administrador'),
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
                show: true,
                icon: Calendar,
            },
            {
                title: 'Por Demanda',
                href: solicituds.index({
                    query: { tipo: 'Mantenimiento Correctivo' },
                }),
                icon: AlertCircle,
                show: true,
            },
            {
                title: 'Todas las Solicitudes',
                href: solicituds.index(),
                icon: List,
                show: true,
            },
            {
                title: 'Cronograma',
                href: solicituds.cronograma(),
                icon: Calendar,
                show: validateRole('Administrador'),
            }
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
            <NavFooter :items="footerNavItems"  v-if="validateRole('Administrador')"/>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
