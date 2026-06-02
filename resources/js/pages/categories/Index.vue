<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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

const isDialogOpen = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
});

const openCreateDialog = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const openEditDialog = (category: Category) => {
    isEditing.value = true;
    editingId.value = category.id;
    form.name = category.name;
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(`/categories/${editingId.value}`, {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/categories', {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    }
};

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
            <Button @click="openCreateDialog">Add Category</Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold">No</th>
                        <th class="p-4 text-sm font-semibold">Category Name</th>
                        <th class="p-4 text-sm font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="(category, index) in categories" :key="category.id" class="hover:bg-muted/30 transition-colors">
                        <td class="p-4 text-sm">{{ index + 1 }}</td>
                        <td class="p-4 text-sm font-medium">{{ category.name }}</td>
                        <td class="p-4 text-right">
                            <Button variant="ghost" size="sm" class="mr-2 text-blue-500 hover:text-blue-600" @click="openEditDialog(category)">
                                Edit
                            </Button>
                            <Button variant="ghost" size="sm" class="text-red-500 hover:text-red-600" @click="deleteCategory(category.id)">
                                Delete
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="categories.length === 0">
                        <td colspan="3" class="p-8 text-center text-muted-foreground">No categories found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Dialog v-model:open="isDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <form @submit.prevent="submit">
                    <DialogHeader>
                        <DialogTitle>{{ isEditing ? 'Edit Category' : 'Add New Category' }}</DialogTitle>
                        <DialogDescription>
                            {{ isEditing ? 'Update the category name below.' : 'Enter a name for the new category.' }}
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="e.g. Science Fiction"
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ isEditing ? 'Save Changes' : 'Create Category' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
