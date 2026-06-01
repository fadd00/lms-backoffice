<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: '/users',
            },
        ],
    },
});

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

defineProps<{
    users: User[];
}>();

</script>

<template>
    <Head title="Users" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">User Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground">Add User</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full text-left">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="user in users" :key="user.id">
                        <td class="p-3">{{ user.name }}</td>
                        <td class="p-3">{{ user.email }}</td>
                        <td class="p-3">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="user.role === 'Admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="p-3">
                            <button class="mr-2 text-blue-500 hover:underline">Edit</button>
                            <button class="text-red-500 hover:underline">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
