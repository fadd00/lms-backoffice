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
                title: 'Members',
                href: '/members',
            },
        ],
    },
});

interface Member {
    id: number;
    member_id: string;
    name: string;
    email: string;
    phone: string;
}

defineProps<{
    members: Member[];
}>();

const isDialogOpen = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    member_id: '',
    name: '',
    email: '',
    phone: '',
});

const openCreateDialog = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const openEditDialog = (member: Member) => {
    isEditing.value = true;
    editingId.value = member.id;
    form.member_id = member.member_id;
    form.name = member.name;
    form.email = member.email;
    form.phone = member.phone || '';
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(`/members/${editingId.value}`, {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/members', {
            onSuccess: () => {
                isDialogOpen.value = false;
                form.reset();
            },
        });
    }
};

const deleteMember = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
        router.delete(`/members/${id}`);
    }
};
</script>

<template>
    <Head title="Members" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Member Management</h1>
            <Button @click="openCreateDialog">Add Member</Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-card">
            <table class="w-full text-left">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">Member ID</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">Name</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">Email</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider">Phone</th>
                        <th class="p-4 text-sm font-semibold text-muted-foreground uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sidebar-border/70 dark:divide-sidebar-border">
                    <tr v-for="member in members" :key="member.id" class="hover:bg-muted/30 transition-colors">
                        <td class="p-4 text-sm font-mono text-muted-foreground">{{ member.member_id }}</td>
                        <td class="p-4 text-sm font-medium">{{ member.name }}</td>
                        <td class="p-4 text-sm">{{ member.email }}</td>
                        <td class="p-4 text-sm">{{ member.phone || '-' }}</td>
                        <td class="p-4 text-right">
                            <Button variant="ghost" size="sm" class="mr-2 text-blue-500 hover:text-blue-600" @click="openEditDialog(member)">
                                Edit
                            </Button>
                            <Button variant="ghost" size="sm" class="text-red-500 hover:text-red-600" @click="deleteMember(member.id)">
                                Delete
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="members.length === 0">
                        <td colspan="5" class="p-8 text-center text-muted-foreground">No members found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Dialog v-model:open="isDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <form @submit.prevent="submit">
                    <DialogHeader>
                        <DialogTitle>{{ isEditing ? 'Edit Member' : 'Add New Member' }}</DialogTitle>
                        <DialogDescription>
                            {{ isEditing ? 'Update member details below.' : 'Enter details for the new member.' }}
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <Label for="member_id">Member ID</Label>
                            <Input id="member_id" v-model="form.member_id" placeholder="MBR001" :disabled="isEditing" />
                            <p v-if="form.errors.member_id" class="text-xs text-red-500">{{ form.errors.member_id }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" placeholder="Full Name" />
                            <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" v-model="form.email" placeholder="email@example.com" />
                            <p v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="phone">Phone</Label>
                            <Input id="phone" v-model="form.phone" placeholder="08123456789" />
                            <p v-if="form.errors.phone" class="text-xs text-red-500">{{ form.errors.phone }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ isEditing ? 'Save Changes' : 'Register Member' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
