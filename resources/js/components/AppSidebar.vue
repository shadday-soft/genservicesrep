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
import  clients  from '@/routes/clients';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Building, LayoutGrid, Users, SquareUser, CircuitBoard } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import sucursals from '@/routes/sucursals';
import equipos from '@/routes/equipos';
import solicituds from '@/routes/solicituds';
import actividads from '@/routes/actividads';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Actividades',
        href: actividads.index(),
        icon: LayoutGrid,
    },
    {
        title: 'Clientes',
        href: clients.index(),
        icon: SquareUser,
    },
    {
        title: 'Sucursales',
        href: sucursals.index(),
        icon: Building,
    },
    {
        title: 'Equipos',
        href: equipos.index(),
        icon: CircuitBoard,
    },
    {
        title: 'Solicitudes',
        href:  solicituds.index(),
        icon: BookOpen,
    }



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
