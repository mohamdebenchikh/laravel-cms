<template>
    <div>
        <Modal ref="modalRef" :title="$t('add_new_folder')">
            <template #body>
                <div class="mb-3">
                    <label for="name" class="form-label">{{ $t('folder_name') }}</label>
                    <input type="text" id='name' name="name" v-model="form.name" class='form-control' :class="[form.errors.name ? 'is-invalid' : '' ]">
                    <span v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</span>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-secondary" @click="closeModal">{{ $t('cancel') }}</button>
                <button class="btn btn-primary" @click="create">{{ $t('create') }}</button>
            </template>
        </Modal>

        <button class="btn btn-primary btn-sm" @click="showModal">{{ $t('add_new_folder') }}</button>
    </div>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {useToast} from 'vue-toastification'

const props = defineProps({
    folder:String,
})

const toast = useToast()
const modalRef = ref(null)
const form = useForm({name:'',path:''})

const emits = defineEmits(['created']);
const showModal = () => {
    modalRef.value.show();
}

const closeModal = () => {
    modalRef.value.hide();
}

const create = () => {
    form.path = props.folder;
    form.post(route('admin.file-manager.createFolder'),{
        preserveState:true,
        preserveScroll:true,
        onSuccess(page){
            modalRef.value.hide();
            toast.success(page.props.success);
            form.reset()
            emits('created',true)
        }
    });
}


</script>