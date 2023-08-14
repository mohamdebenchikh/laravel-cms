<template>
    <div class="modal fade" aria-hidden="true" tabindex="-1" role="dialog" ref="modalElement">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <slot name="body"></slot>
                </div>

                <div class="modal-footer" v-if="$slots.footer">
                    <slot name="footer"></slot>
                </div>
            </div>

        </div>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import { Modal } from 'bootstrap';

const modalElement = ref(null)
let modalObject = null

const props = defineProps({
    title: String,
})

const _show = () => {
    modalObject.show();
}

const _hide = () => {
    modalObject.hide();
}

onMounted(() => {
    modalObject = new Modal(modalElement.value);
})

defineExpose({ show: _show, hide: _hide });

</script>