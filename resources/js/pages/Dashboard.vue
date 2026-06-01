<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { Book, Bookmark, Users, ClipboardList } from 'lucide-vue-next';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

defineProps<{
    stats: {
        total_books: number;
        total_categories: number;
        total_members: number;
        active_borrowings: number;
    };
    recent_borrowings: any[];
}>();

</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <!-- Statistic Cards -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-sidebar-border/70 p-6 shadow-sm dark:border-sidebar-border bg-card">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-blue-100 p-2 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <Book :size="24" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Total Books</p>
                        <h2 class="text-2xl font-bold">{{ stats.total_books }}</h2>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-6 shadow-sm dark:border-sidebar-border bg-card">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <Bookmark :size="24" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Categories</p>
                        <h2 class="text-2xl font-bold">{{ stats.total_categories }}</h2>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-6 shadow-sm dark:border-sidebar-border bg-card">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-purple-100 p-2 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                        <Users :size="24" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Members</p>
                        <h2 class="text-2xl font-bold">{{ stats.total_members }}</h2>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-6 shadow-sm dark:border-sidebar-border bg-card">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-orange-100 p-2 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                        <ClipboardList :size="24" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Active Borrowings</p>
                        <h2 class="text-2xl font-bold">{{ stats.active_borrowings }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Borrowings Table -->
        <div class="mt-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card overflow-hidden">
            <div class="p-4 border-b border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="font-semibold italic">Recent Borrowings</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="p-4 text-xs font-medium text-muted-foreground">Member</th>
                            <th class="p-4 text-xs font-medium text-muted-foreground">Book</th>
                            <th class="p-4 text-xs font-medium text-muted-foreground">Borrow Date</th>
                            <th class="p-4 text-xs font-medium text-muted-foreground">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                        <tr v-for="borrowing in recent_borrowings" :key="borrowing.id" class="text-sm">
                            <td class="p-4 font-medium">{{ borrowing.member?.name }}</td>
                            <td class="p-4">{{ borrowing.book?.title }}</td>
                            <td class="p-4 text-muted-foreground">{{ borrowing.borrow_date }}</td>
                            <td class="p-4">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold" 
                                      :class="borrowing.status === 'Borrowed' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'">
                                    {{ borrowing.status }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="recent_borrowings.length === 0">
                            <td colspan="4" class="p-8 text-center text-muted-foreground italic">No recent activities found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

