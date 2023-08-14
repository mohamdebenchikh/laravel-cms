<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('admin') }} | {{ post.title }}</title>
        </template>

        <div class='row justify-content-center'>
            <div class='col-md-8'>
                <div class="mb-4">
                    <Link class="btn btn-success" :href="route('admin.posts.edit', post.id)">{{ $t('edit') }}</Link>
                </div>
                <div class='card overflow-hidden'>
                    <img v-if="post.image" :src="post.image" :alt="post.title" class="card-image w-100" style="max-height: 300px;object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex py-2">
                            {{ post.user.name }} - {{ post.created_at }} - {{ post.category.name }}
                        </div>
                        <hr>
                        <h3 class='card-title'>{{ post.title }}</h3>
                        <hr>
                        
                        <div v-html="post.content"></div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
})

</script>