<template>
    <div class="alert alert-dismissible fade show" :class="`alert-${type}`" role="alert">
        <slot></slot>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

    </div>
</template>
<script setup>
const props = defineProps({
    type: { tyep: "success" | "danger" | "primary" | "waring" | "info", default: "info" }
})

</script>