<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('admin') }} | {{ $t('settings') }}</title>
        </template>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $t('settings') }}</span>
                <button class="btn btn-primary btn-sm" @click='create'>{{ $t('add_new_setting') }}</button>
            </div>
            <div class="card-body">
                <div class="responsive-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ $t('setting_key') }}</th>
                                <th>{{ $t('setting_value') }}</th>
                                <th class="text-center">{{ $t('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="setting in settings" :key="setting.id">
                                <td>{{ setting.key }}</td>
                                <td>{{ setting.value }}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-sm me-2" @click="edit(setting)">{{ $t('edit') }}</button>
                                    <button class="btn btn-danger btn-sm" @click="showDeleteDialog(setting.id)">{{
                                        $t('delete') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <Modal :title="$t('delete')" ref="deleteDialog">
                <template #body>
                    <p>Are you sure you want to delete this setting?</p>
                </template>
                <template #footer>
                    <button class="btn btn-secondary me-2" @click="closeDeleteDialog">{{ $t('cancel') }}</button>
                    <button class="btn btn-danger" @click="deleteIt">{{ $t('yes_delete_it') }}</button>
                </template>
            </Modal>
            <FormModal ref="formModalRef" :edit-mode="editMode" @success='showSuccessMessage'></FormModal>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormModal from './FormModal.vue';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue'
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';


const formModalRef = ref(null);
const editMode = ref(false);
const toast = useToast();

const create = () => {
    editMode.value = false
    formModalRef.value.show(false)
};

const edit = (item) => {
    editMode.value = true;
    formModalRef.value.show(item);
}

const showSuccessMessage = (page) => {
    const successMessage = page.props.success
    toast.success(successMessage);
}

const props = defineProps({
    settings: Array,
});

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
    router.delete(route('admin.settings.destroy', deleteId.value), {
        preserveState: true,
        onSuccess(page) {
            const successMessage = page.props.success
            deleteDialog.value.hide()
            toast.success(successMessage);
        }
    });
}

</script>