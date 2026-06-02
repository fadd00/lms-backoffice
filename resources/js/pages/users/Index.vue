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
                title: 'Users',
                href: '/users',
            },
        ],
    },
});

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

defineProps<{
    users: User[];
}>();

const isDialogOpen = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
    email: '',
    role: 'Pustakawan',
    password: '',
});

const openCreateDialog = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const openEditDialog = (user: User) => {
    isEditing.value = true;
    editingId.value = user.id;
    form.name = user.name;
    form.email = user.email;
    form.role = user.role;
    form.password = '';
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(`/users/${editingId.value}`, {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/users', {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    }
};

const deleteUser = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        router.delete(`/users/${id}`);
    }
};
</script>

<template>
    <Head title="Users" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">User Management</h1>
            <Button @click="openCreateDialog">Add User</Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase">Name</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">Email</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">Role</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="user in users" :key="user.id" class="hover:bg-muted/30 transition-colors">
                        <td class="p-4 text-sm font-medium">{{ user.name }}</td>
                        <td class="p-4 text-sm">{{ user.email }}</td>
                        <td class="p-4 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" 
                                :class="user.role === 'Admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <Button variant="ghost" size="sm" class="mr-2 text-blue-500 hover:text-blue-600" @click="openEditDialog(user)">
                                Edit
                            </Button>
                            <Button variant="ghost" size="sm" class="text-red-500 hover:text-red-600" @click="deleteUser(user.id)">
                                Delete
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="users.length === 0">
                        <td colspan="4" class="p-8 text-center text-muted-foreground">No users found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Dialog v-model:open="isDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <form @submit.prevent="submit">
                    <DialogHeader>
                        <DialogTitle>{{ isEditing ? 'Edit User' : 'Add New User' }}</DialogTitle>
                        <DialogDescription>
                            {{ isEditing ? 'Update user details. Leave password blank to keep current.' : 'Create a new staff or admin account.' }}
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" placeholder="Full Name" />
                            <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" v-model="form.email" placeholder="email@lms.com" />
                            <p v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="role">Role</Label>
                            <Select v-model="form.role">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Role" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Admin">Admin</SelectItem>
                                    <SelectItem value="Pustakawan">Pustakawan</SelectItem>
                                    <SelectItem value="Guest">Guest (Pending Approval)</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.role" class="text-xs text-red-500">{{ form.errors.role }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="password">Password {{ isEditing ? '(Optional)' : '' }}</Label>
                            <Input id="password" type="password" v-model="form.password" placeholder="Min 8 characters" />
                            <p v-if="form.errors.password" class="text-xs text-red-500">{{ form.errors.password }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ isEditing ? 'Save Changes' : 'Create User' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
