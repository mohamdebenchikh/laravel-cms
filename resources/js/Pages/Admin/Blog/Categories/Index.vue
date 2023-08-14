<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('dashboard') }} | {{ $t('categories') }}</title>
        </template>

        <div class="mb-4">
            <h3 class="text-muted">{{ $t('categories') }}</h3>
        </div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between" style="border: none;">
                <span>{{ $t('categories') }}</span>
                <button class="btn btn-sm btn-primary" @click="create">{{ $t('create_new_category') }}</button>
            </div>
            <div class="card-body">
                <DataTable :data='categories' :url='route("admin.categories.index")' :columns="columns">
                    <template #body="{ items }">
                        <tr v-for="item in items" :key='item.id'>
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.slug }}</td>
                            <td>{{ item.created_at }}</td>
                            <td class="text-center">
                                <button class="btn btn-success me-2 btn-sm" @click="edit(item)">{{ $t('edit') }}</button>
                                <button class="btn btn-danger btn-sm" @click="showDeleteDialog(item.id)">{{ $t('delete')
                                }}</button>
                            </td>
                        </tr>
                    </template>
                </DataTable>
            </div>
        </div>

        <FormModal ref='formModal' :edit-mode="editMode" @success='showSuccessMessage'></FormModal>

        <Modal :title="$t('delete')" ref="deleteDialog">
            <template #body>
                <p>Are you sure you want to delete this category?</p>
            </template>
            <template #footer>
                <button class="btn btn-secondary me-2" @click="closeDeleteDialog">{{ $t('cancel') }}</button>
                <button class="btn btn-danger" @click="deleteIt">{{ $t('yes_delete_it') }}</button>
            </template>
        </Modal>

    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
import FormModal from "./FormModal.vue";
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toastification'

const props = defineProps({ categories: Object });
const formModal = ref(null);
const editMode = ref(false);
const toast = useToast();

const create = () => {
    editMode.value = false
    formModal.value.show(false)
};

const edit = (item) => {
    editMode.value = true;
    formModal.value.show(item);
}

const showSuccessMessage = (page) => {
    const successMessage = page.props.success
    reloadData();
    toast.success(successMessage);
}

const reloadData = () => {
    router.get(route('admin.categories.index'), {}, {
        preserveState: false,
        replace: true
    });
}

const columns = [
    { text: "#", data: 'id' },
    { text: "name", data: 'name' },
    { text: "slug", data: 'slug' },
    { text: "created_at", data: 'created_at' },
    { text: 'action', sortable: false },
]


const deleteDialog = ref(null)
const deleteId = ref(null)

const showDeleteDialog = (id) => {
    deleteId.value = id;
    deleteDialog.value.show();
}

const closeDeleteDialog = () => {
    deleteId.value = null
    deleteDialog.value.hide();
}

const deleteIt = () => {
    router.delete(route('admin.categories.destroy', deleteId.value), {
        preserveState: true,
        onSuccess(page) {
            const successMessage = page.props.success
            deleteDialog.value.hide()
            reloadData();
            toast.success(successMessage);
        }
    });
}

</script>