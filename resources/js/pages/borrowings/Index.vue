<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Borrowings',
                href: '/borrowings',
            },
        ],
    },
});

interface Borrowing {
    id: number;
    transaction_code: string;
    member: { name: string };
    book: { title: string };
    borrow_date: string;
    return_date: string;
    status: string;
}

defineProps<{
    borrowings: Borrowing[];
}>();
</script>

<template>
    <Head title="Borrowings" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Borrowing Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground">New Borrowing</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full text-left">
                <thead class="bg-muted">
                    <tr>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Code</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Member</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Book</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Borrow Date</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Return Date</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Status</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="borrowing in borrowings" :key="borrowing.id" class="text-sm">
                        <td class="p-3 font-mono">{{ borrowing.transaction_code }}</td>
                        <td class="p-3">{{ borrowing.member?.name }}</td>
                        <td class="p-3">{{ borrowing.book?.title }}</td>
                        <td class="p-3">{{ borrowing.borrow_date }}</td>
                        <td class="p-3">{{ borrowing.return_date }}</td>
                        <td class="p-3">
                            <span class="rounded-md px-2 py-0.5 text-xs font-semibold" 
                                  :class="borrowing.status === 'Borrowed' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800'">
                                {{ borrowing.status }}
                            </span>
                        </td>
                        <td class="p-3">
                            <button v-if="borrowing.status === 'Borrowed'" class="text-blue-500 hover:underline">Return</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
