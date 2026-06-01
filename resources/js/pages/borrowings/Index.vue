<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

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

const returnBook = (id: number) => {
    if (confirm('Apakah Anda yakin ingin mengembalikan buku ini?')) {
        router.post(`/borrowings/${id}/return`);
    }
};
</script>

<template>
    <Head title="Borrowings" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Borrowing Management</h1>
            <button class="rounded-md bg-primary px-4 py-2 text-primary-foreground text-sm font-medium">New Borrowing</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold">Code</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold">Member</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold">Book</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold">Borrow Date</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold">Return Date</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold">Status</th>
                        <th class="p-3 text-xs uppercase text-muted-foreground font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="borrowing in borrowings" :key="borrowing.id" class="text-sm hover:bg-muted/30 transition-colors">
                        <td class="p-3 font-mono font-medium">{{ borrowing.transaction_code }}</td>
                        <td class="p-3 text-muted-foreground font-medium text-foreground">{{ borrowing.member?.name }}</td>
                        <td class="p-3">{{ borrowing.book?.title }}</td>
                        <td class="p-3">{{ borrowing.borrow_date }}</td>
                        <td class="p-3">{{ borrowing.return_date }}</td>
                        <td class="p-3">
                            <span class="rounded-md px-2 py-0.5 text-xs font-semibold" 
                                  :class="borrowing.status === 'Borrowed' ? 'bg-orange-100 text-orange-800 dark:bg-orange-950/50 dark:text-orange-400' : 'bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-400'">
                                {{ borrowing.status }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <button 
                                v-if="borrowing.status === 'Borrowed'" 
                                @click="returnBook(borrowing.id)"
                                class="text-blue-500 hover:text-blue-600 font-medium transition-colors"
                            >
                                Return Asset
                            </button>
                            <span v-else class="text-muted-foreground italic text-xs">Completed</span>
                        </td>
                    </tr>
                    <tr v-if="borrowings.length === 0">
                        <td colspan="7" class="p-8 text-center text-muted-foreground">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
