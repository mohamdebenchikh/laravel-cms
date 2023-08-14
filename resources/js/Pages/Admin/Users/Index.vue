<template>
    <AdminLayout>
        <template #head>
            <title>Admin | Users</title>
        </template>

        <div class="mb-3">
            <h3>{{ $t('users') }}</h3>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <span>{{ $t('users') }}</span>
                    <Link :href="route('admin.users.create')" class="btn btn-primary btn-sm">{{ $t('add_new_user') }}</Link>
                </div>
            </div>
            <div class="card-body">
                <DataTable :data='users' :columns="columns" :url='route("admin.users.index")'>
                    <template #body='{ items }'>
                        <tr v-for="item in items" :key='item.id'>
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.email }}</td>
                            <td>{{ item.created_at }}</td>
                            <td class="text-center">
                                <Link :href="route('admin.users.edit', item.id)" class="btn btn-success btn-sm me-1">{{ $t('edit') }}
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
                <p>Are you sure you want to delete this user?</p>
            </template>
            <template #footer>
                <button class="btn btn-secondary me-2" @click="closeDeleteDialog">{{ $t('cancel') }}</button>
                <button class="btn btn-danger" @click="deleteUser">{{ $t('yes_delete_it') }}</button>
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
const deleteUserId = ref(null)

const showDeleteDialog = (id) => {
    deleteUserId.value = id;
    deleteDialog.value.show();
}

const closeDeleteDialog = () => {
    deleteUserId.value = null
    deleteDialog.value.hide();
}

const deleteUser = () => {
    router.delete(route('admin.users.destroy', deleteUserId.value), {
        onSuccess() {
            deleteUserId.value = null;
            deleteDialog.value.hide();
        }
    });
}

const props = defineProps({ users: Object });
const columns = [
    { text: 'ID', data: 'id' },
    { text: 'name', data: 'name' },
    { text: 'email', data: 'email' },
    { text: 'created_at', data: 'created_at' },
    { text: 'action', sortable: false },
]


</script>