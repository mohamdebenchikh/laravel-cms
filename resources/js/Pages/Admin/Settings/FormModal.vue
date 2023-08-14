<template>
    <Modal ref="modalDialog" :title="editMode ? $t('edit') : $t('add_new_setting')">
        <template #body>
            <div class="mb-3">
                <label for="key" class="form-label">{{ $t('setting_key') }}</label>
                <input type="text" name="key" id="key" v-model="form.key" class="form-control"
                    :class="[form.errors.key ? 'is-invalid' : '']">
                <span class="invalid-feedback">{{ form.errors.key }}</span>
            </div>

            <div class="mb-3">
                <label for="value" class="form-label">{{ $t('setting_value') }}</label>
                <input type="text" name="value" id="value" v-model="form.value" class="form-control"
                    :class="[form.errors.value ? 'is-invalid' : '']">
                <span class="invalid-feedback">{{ form.errors.value }}</span>
            </div>
           
        </template>
        <template #footer>
            <button class="btn btn-secondary" @click="close">{{ $t('cancel') }}</button>
            <button class="btn" :class="[editMode ? 'btn-success' : 'btn-primary']" @click="submit">{{ editMode ? $t('save') :
                $t('add') }}</button>
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
    key: '',
    value: ''
});

const close = () => {
    form.id = '';
    form.key = '';
    form.value = '';
    modalDialog.value.hide();
    emits('close');
}

const show = (item = null) => {
    if (item) {
        form.id = item.id
        form.key = item.key
        form.value = item.value
    } else {
        form.id = '';
        form.key = '';
        form.value = '';

    }

    modalDialog.value.show();
}

const create = () => {
    form.post(route('admin.settings.store'), {
        onSuccess(page) {
            close();
            emits('success', page)
        }
    });
}

const update = () => {
    form.put(route('admin.settings.update', form.id), {
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