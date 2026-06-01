<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Books',
                href: '/books',
            },
        ],
    },
});

interface Book {
    id: number;
    book_code: string;
    title: string;
    author: string;
    category: { name: string };
    stock: number;
}

defineProps<{
    books: Book[];
}>();
</script>

<template>
    <Head title="Books" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Book Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground">Add Book</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full text-left">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3">Code</th>
                        <th class="p-3">Title</th>
                        <th class="p-3">Author</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Stock</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="book in books" :key="book.id">
                        <td class="p-3 font-mono text-sm">{{ book.book_code }}</td>
                        <td class="p-3 font-semibold">{{ book.title }}</td>
                        <td class="p-3 text-sm text-muted-foreground">{{ book.author }}</td>
                        <td class="p-3 text-sm">{{ book.category?.name }}</td>
                        <td class="p-3 text-sm">{{ book.stock }}</td>
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
