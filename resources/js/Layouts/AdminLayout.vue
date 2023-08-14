<template>
    <Head>
        <slot name="head"></slot>
    </Head>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container py-4">

            <Alert v-if="success" type='success' >{{ success }}</Alert>
            <Alert v-if="error" type='danger' >{{ error }}</Alert>
            <slot></slot>
        </div>
    </div>
</template>
<script setup>
import AdminHeader from '@/Components/AdminHeader.vue';
import Alert from '@/Components/Alert.vue';
import { Head, usePage } from '@inertiajs/vue3'

const success = usePage().props.success;
const error = usePage().props.error;

</script>