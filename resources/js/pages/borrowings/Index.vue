<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

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
    member: { id: number; name: string };
    book: { id: number; title: string };
    borrow_date: string;
    return_date: string;
    status: string;
}

interface Member {
    id: number;
    name: string;
}

interface Book {
    id: number;
    title: string;
    stock: number;
}

defineProps<{
    borrowings: Borrowing[];
    members: Member[];
    books: Book[];
}>();

const isDialogOpen = ref(false);

const form = useForm({
    member_id: '',
    book_id: '',
    borrow_date: new Date().toISOString().split('T')[0],
    return_date: '',
});

const openCreateDialog = () => {
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    form.post('/borrowings', {
        onSuccess: () => {
            isDialogOpen.value = false;
            form.reset();
        },
    });
};

const returnBook = (id: number) => {
    if (confirm('Apakah Anda yakin ingin mengembalikan buku ini?')) {
        router.post(`/borrowings/${id}/return`);
    }
};

const deleteBorrowing = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus data transaksi ini?')) {
        router.delete(`/borrowings/${id}`);
    }
};
</script>

<template>
    <Head title="Borrowings" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Borrowing Management</h1>
            <Button @click="openCreateDialog">New Borrowing</Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-4 text-xs font-semibold text-muted-foreground uppercase">Code</th>
                        <th class="p-4 text-xs font-semibold text-muted-foreground uppercase">Member</th>
                        <th class="p-4 text-xs font-semibold text-muted-foreground uppercase">Book</th>
                        <th class="p-4 text-xs font-semibold text-muted-foreground uppercase">Borrow Date</th>
                        <th class="p-4 text-xs font-semibold text-muted-foreground uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="borrowing in borrowings" :key="borrowing.id" class="text-sm hover:bg-muted/30 transition-colors">
                        <td class="p-4 font-mono font-medium">{{ borrowing.transaction_code }}</td>
                        <td class="p-4 font-medium">{{ borrowing.member?.name }}</td>
                        <td class="p-4">
                            <div>{{ borrowing.book?.title }}</div>
                            <div class="text-[10px] text-muted-foreground">Return by: {{ borrowing.return_date }}</div>
                        </td>
                        <td class="p-4">{{ borrowing.borrow_date }}</td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-tight" 
                                      :class="borrowing.status === 'Borrowed' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'">
                                    {{ borrowing.status }}
                                </span>
                                <Button v-if="borrowing.status === 'Borrowed'" variant="outline" size="sm" @click="returnBook(borrowing.id)">
                                    Return
                                </Button>
                                <Button variant="ghost" size="sm" class="text-red-500" @click="deleteBorrowing(borrowing.id)">
                                    Delete
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="borrowings.length === 0">
                        <td colspan="5" class="p-8 text-center text-muted-foreground">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Dialog v-model:open="isDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <form @submit.prevent="submit">
                    <DialogHeader>
                        <DialogTitle>New Borrowing Transaction</DialogTitle>
                        <DialogDescription>
                            Create a new record for a member borrowing a book.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <Label>Member</Label>
                            <Select v-model="form.member_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Member" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="m in members" :key="m.id" :value="String(m.id)">
                                        {{ m.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.member_id" class="text-xs text-red-500">{{ form.errors.member_id }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label>Book</Label>
                            <Select v-model="form.book_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Book" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="b in books" :key="b.id" :value="String(b.id)">
                                        {{ b.title }} (Stock: {{ b.stock }})
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.book_id" class="text-xs text-red-500">{{ form.errors.book_id }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="borrow_date">Borrow Date</Label>
                                <Input id="borrow_date" type="date" v-model="form.borrow_date" />
                                <p v-if="form.errors.borrow_date" class="text-xs text-red-500">{{ form.errors.borrow_date }}</p>
                            </div>
                            <div class="grid gap-2">
                                <Label for="return_date">Return Date</Label>
                                <Input id="return_date" type="date" v-model="form.return_date" />
                                <p v-if="form.errors.return_date" class="text-xs text-red-500">{{ form.errors.return_date }}</p>
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">Create Record</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
