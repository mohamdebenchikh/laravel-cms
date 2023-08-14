<template>
    <AdminLayout>
        <template #head>
            <title> {{ $t('dashboard')  }} | {{  $t('pages')  }}</title>
        </template>

        <div class="mb-3">
            <h3>{{ $t('pages') }}</h3>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <span>{{ $t('pages') }}</span>
                    <Link :href="route('admin.pages.create')" class="btn btn-primary btn-sm">{{ $t('create_new_page') }}</Link>
                </div>
            </div>
            <div class="card-body">
                <DataTable :data='pages' :columns="columns" :url='route("admin.pages.index")'>
                    <template #body='{ items }'>
                        <tr v-for="item in items" :key='item.id'>
                            <td>{{ item.id }}</td>
                            <td>{{ item.title }}</td>
                            <td class="text-center">
                                <span v-if='item.status'>PUBLISH</span>
                                <span v-else>DRAFT</span>
                            </td>
                            <td>{{ item.created_at }}</td>
                            <td class="text-center">
                                <Link :href="route('admin.pages.edit', item.id)" class="btn btn-success btn-sm me-1">{{ $t('edit') }}
                                </Link>
                                <button class="btn btn-danger btn-sm" @click="showDeleteDialog(item.id)">{{ $t('delete') }}</button>
                            </td>
                        </tr>
                    </template>
                </DataTable>
            </div>

        </div>

        <Modal title="Delete" ref="deleteDialog">
            <template #body>
                <p>Are you sure you want to delete this page?</p>
            </template>
            <template #footer>
                <button class="btn btn-secondary me-2" @click="closeDeleteDialog">{{ $t('cancel') }}</button>
                <button class="btn btn-danger" @click="deletePost">{{ $t('yes_delete_it') }}</button>
            </template>
        </Modal>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const deleteDialog = ref(null)
const deletePageId = ref(null)

const showDeleteDialog = (id) => {
    deletePageId.value = id;
    deleteDialog.value.show();
}

const closeDeleteDialog = () => {
    deletePageId.value = null
    deleteDialog.value.hide();
}

const deletePost = () => {
    router.delete(route('admin.pages.destroy', deletePageId.value), {
        onSuccess() {
            deletePageId.value = null;
            deleteDialog.value.hide();
        }
    });
}

const props = defineProps({ pages: Object });
const columns = [
    { text: 'ID', data: 'id' },
    { text: 'title', data: 'title' },
    { text: 'status', sortable:false},
    { text: 'created_at', data: 'created_at' },
    { text: 'action', sortable: false },
]


</script>