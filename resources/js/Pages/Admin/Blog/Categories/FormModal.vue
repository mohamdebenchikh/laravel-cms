<template>
    <Modal ref="modalDialog" :title="editMode ? $t('edit') : $t('create_new_category')">
        <template #body>
            <div class="mb-3">
                <label for="name" class="form-label">{{ $t('category_name') }}</label>
                <input type="text" name="name" id="name" v-model="form.name" class="form-control"
                    :class="[form.errors.name ? 'is-invalid' : '']">
                <span class="invalid-feedback">{{ form.errors.name }}</span>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ $t('description') }}</label>
                <textarea name="description" id="description" v-model="form.description" class="form-control"
                    :class="[form.errors.description ? 'is-invalid' : '']"></textarea>
                <span class="invalid-feedback">{{ form.errors.description }}</span>
            </div>
        </template>
        <template #footer>
            <button class="btn btn-secondary" @click="close">{{ $t('cancel') }}</button>
            <button class="btn" :class="[editMode ? 'btn-success' : 'btn-primary']" @click="submit">{{ editMode ? $t('save') :
                $t('create') }}</button>
        </template>
    </Modal>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    editMode: {
        type: Boolean,
        default: false
    },
})

const emits = defineEmits(['close', 'show', 'success']);

const modalDialog = ref(null);
const form = useForm({
    id: '',
    name: '',
    description: ''
});

const close = () => {
    form.id = '';
    form.name = '';
    form.description = '';
    modalDialog.value.hide();
    emits('close');
}

const show = (item = null) => {
    if (item) {
        form.id = item.id
        form.name = item.name
        form.description = item.description
    } else {
        form.id = '';
        form.name = '';
        form.description = '';

    }

    modalDialog.value.show();
}

const create = () => {
    form.post(route('admin.categories.store'), {
        onSuccess(page) {
            close();
            emits('success', page)
        }
    });
}

const update = () => {
    form.put(route('admin.categories.update', form.id), {
        onSuccess(page) {
            close()
            emits('success', page)
        }
    });
}

const submit = () => {

    if (props.editMode === false) { create() } else { update() }
};

defineExpose({ show, close })

</script>