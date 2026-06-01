<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { 
    BookOpen, 
    FolderGit2, 
    LayoutGrid, 
    Users, 
    Library, 
    Bookmark, 
    ClipboardList,
    UserCircle
} from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
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
import type { NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const userRole = page.props.auth.user.role;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Users',
        href: '/users',
        icon: Users,
    },
];

const libraryNavItems: NavItem[] = [
    {
        title: 'Categories',
        href: '/categories',
        icon: Bookmark,
    },
    {
        title: 'Books',
        href: '/books',
        icon: Library,
    },
    {
        title: 'Members',
        href: '/members',
        icon: UserCircle,
    },
];

const transactionNavItems: NavItem[] = [
    {
        title: 'Borrowings',
        href: '/borrowings',
        icon: ClipboardList,
    },
];

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
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
            <NavMain v-if="userRole === 'Admin'" label="User Management" :items="adminNavItems" />
            <NavMain label="Library" :items="libraryNavItems" />
            <NavMain label="Transactions" :items="transactionNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
