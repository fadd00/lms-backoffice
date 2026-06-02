<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Clock } from 'lucide-vue-next';
</script>

<template>
    <Head title="Menunggu Persetujuan" />
    <div class="flex min-h-screen flex-col items-center justify-center bg-background p-4 text-center">
        <div class="mb-6 rounded-full bg-orange-100 p-6 dark:bg-orange-900/20">
            <Clock class="h-12 w-12 text-orange-600 dark:text-orange-400" />
        </div>
        <h1 class="mb-2 text-3xl font-bold">Akun Menunggu Persetujuan</h1>
        <p class="mb-8 max-w-md text-muted-foreground">
            Terima kasih telah mendaftar. Akun Anda saat ini sedang menunggu verifikasi dari Admin. 
            Silakan hubungi administrator untuk mempercepat proses persetujuan.
        </p>
        <div class="flex gap-4">
            <Button as-child variant="outline">
                <Link href="/logout" method="post" as="button">Logout</Link>
            </Button>
            <Button @click="() => window.location.reload()">Refresh Halaman</Button>
        </div>
    </div>
</template>