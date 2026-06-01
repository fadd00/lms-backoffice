<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Members',
                href: '/members',
            },
        ],
    },
});

interface Member {
    id: number;
    member_id: string;
    name: string;
    email: string;
    phone: string;
}

defineProps<{
    members: Member[];
}>();
</script>

<template>
    <Head title="Members" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Member Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground">Add Member</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full text-left">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3">Member ID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Phone</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="member in members" :key="member.id">
                        <td class="p-3 text-sm font-mono text-muted-foreground">{{ member.member_id }}</td>
                        <td class="p-3">{{ member.name }}</td>
                        <td class="p-3">{{ member.email }}</td>
                        <td class="p-3">{{ member.phone }}</td>
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
