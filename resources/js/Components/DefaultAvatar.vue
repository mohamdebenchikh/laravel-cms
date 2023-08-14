<template>
    <div class="rounded-circle bg-primary text-white"  :style="`width: ${size}px;height:${size}px;border:solid 1px #eee`">
<div class="d-flex align-items-center justify-content-center h-100 w-100">
<slot></slot>
</div>
        
</div>
</template>
<script setup>

const props = defineProps({
    size: {
        type: Number,
        default: 32
    }
})

</script>