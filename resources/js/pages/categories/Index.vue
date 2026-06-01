<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

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

const deleteCategory = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        router.delete(`/categories/${id}`);
    }
};
</script>

<template>
    <Head title="Categories" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Category Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground text-sm font-medium">Add Category</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-3 text-sm font-semibold">No</th>
                        <th class="p-3 text-sm font-semibold">Category Name</th>
                        <th class="p-3 text-sm font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="(category, index) in categories" :key="category.id" class="hover:bg-muted/30 transition-colors">
                        <td class="p-3 text-sm">{{ index + 1 }}</td>
                        <td class="p-3 text-sm font-medium">{{ category.name }}</td>
                        <td class="p-3 text-right">
                            <button class="mr-3 text-sm text-blue-500 hover:text-blue-600 font-medium">Edit</button>
                            <button 
                                @click="deleteCategory(category.id)"
                                class="text-sm text-red-500 hover:text-red-600 font-medium"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="categories.length === 0">
                        <td colspan="3" class="p-8 text-center text-muted-foreground">No categories found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
