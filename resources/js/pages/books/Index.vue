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
                title: 'Books',
                href: '/books',
            },
        ],
    },
});

interface Category {
    id: number;
    name: string;
}

interface Book {
    id: number;
    book_code: string;
    title: string;
    author: string;
    category_id: number;
    category: Category;
    stock: number;
}

const props = defineProps<{
    books: Book[];
    categories: Category[];
}>();

const isDialogOpen = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    book_code: '',
    title: '',
    author: '',
    category_id: '',
    stock: 0,
});

const openCreateDialog = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const openEditDialog = (book: Book) => {
    isEditing.value = true;
    editingId.value = book.id;
    form.book_code = book.book_code;
    form.title = book.title;
    form.author = book.author;
    form.category_id = String(book.category_id);
    form.stock = book.stock;
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(`/books/${editingId.value}`, {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/books', {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    }
};

const deleteBook = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
        router.delete(`/books/${id}`);
    }
};
</script>

<template>
    <Head title="Books" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Book Management</h1>
            <Button @click="openCreateDialog">Add Book</Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold">Code</th>
                        <th class="p-4 text-sm font-semibold">Title</th>
                        <th class="p-4 text-sm font-semibold">Author</th>
                        <th class="p-4 text-sm font-semibold">Category</th>
                        <th class="p-4 text-sm font-semibold">Stock</th>
                        <th class="p-4 text-sm font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="book in books" :key="book.id" class="hover:bg-muted/30 transition-colors">
                        <td class="p-4 text-sm font-mono">{{ book.book_code }}</td>
                        <td class="p-4 text-sm font-medium">{{ book.title }}</td>
                        <td class="p-4 text-sm text-muted-foreground">{{ book.author }}</td>
                        <td class="p-4 text-sm">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ book.category?.name }}
                            </span>
                        </td>
                        <td class="p-4 text-sm">{{ book.stock }}</td>
                        <td class="p-4 text-right">
                            <Button variant="ghost" size="sm" class="mr-2 text-blue-500 hover:text-blue-600" @click="openEditDialog(book)">
                                Edit
                            </Button>
                            <Button variant="ghost" size="sm" class="text-red-500 hover:text-red-600" @click="deleteBook(book.id)">
                                Delete
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="books.length === 0">
                        <td colspan="6" class="p-8 text-center text-muted-foreground">No books found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Dialog v-model:open="isDialogOpen">
            <DialogContent class="sm:max-w-[500px]">
                <form @submit.prevent="submit">
                    <DialogHeader>
                        <DialogTitle>{{ isEditing ? 'Edit Book' : 'Add New Book' }}</DialogTitle>
                        <DialogDescription>
                            {{ isEditing ? 'Update book information below.' : 'Enter the details for the new book.' }}
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="book_code">Book Code</Label>
                                <Input id="book_code" v-model="form.book_code" placeholder="e.g. BOK001" :disabled="isEditing" />
                                <p v-if="form.errors.book_code" class="text-xs text-red-500">{{ form.errors.book_code }}</p>
                            </div>
                            <div class="grid gap-2">
                                <Label for="stock">Stock</Label>
                                <Input id="stock" type="number" v-model="form.stock" />
                                <p v-if="form.errors.stock" class="text-xs text-red-500">{{ form.errors.stock }}</p>
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" placeholder="Book Title" />
                            <p v-if="form.errors.title" class="text-xs text-red-500">{{ form.errors.title }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="author">Author</Label>
                            <Input id="author" v-model="form.author" placeholder="Author Name" />
                            <p v-if="form.errors.author" class="text-xs text-red-500">{{ form.errors.author }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="category">Category</Label>
                            <Select v-model="form.category_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cat in categories" :key="cat.id" :value="String(cat.id)">
                                        {{ cat.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.category_id" class="text-xs text-red-500">{{ form.errors.category_id }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ isEditing ? 'Save Changes' : 'Create Book' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
