<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Categories',
                href: '/categories',
            },
        ],
    },
});

interface Category {
    id: number;
    name: string;
}

defineProps<{
    categories: Category[];
}>();
</script>

<template>
    <Head title="Categories" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Category Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground">Add Category</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full text-left">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Category Name</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="(category, index) in categories" :key="category.id">
                        <td class="p-3">{{ index + 1 }}</td>
                        <td class="p-3">{{ category.name }}</td>
                        <td class="p-3 text-right">
                            <button class="mr-2 text-blue-500 hover:underline">Edit</button>
                            <button class="text-red-500 hover:underline">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
