<template>
    <div>
        <div v-if="image" class="mb-3">
            <img :src="image" class="w-100 rounded" alt="" style="height: 200px;object-fit: cover;">
        </div>
        <input @change="handleImageChange" type="file" class="d-none" ref="imageInput" accept="image/*">
        <div v-if="image" class="row">
            <div class="col-6">
                <button class="btn btn-light w-100" @click="$refs.imageInput.click()">{{ $t('change_image') }}</button>
            </div>
            <div class="col-6">
                <button class="btn btn-danger w-100" @click="removeImage">{{ $t('remove') }}</button>
            </div>
        </div>
       
        <div v-else @click="$refs.imageInput.click()" style="height: 200px;width: 100%;border: dashed gray 3px;" class="d-flex align-items-center justify-content-center" role="button">
            <span class="text-muted"><i class="bi bi-upload me-2" style="font-size: 20px;"></i> {{ $t('click_to_upload_image') }}</span>
</div>
    </div>
</template>
<script setup>
import axios from 'axios';
import { ref,onMounted } from 'vue';

const image = ref(null);
const emits = defineEmits(['update:modelValue'])
const props = defineProps({
    modelValue:String,
})

const upload = async file => {
    try {
        const formData = new FormData();
        formData.append('image', file);
        const response = await axios.post(route('admin.images.upload'), formData);
        image.value = response.data.url;
        emits('update:modelValue', image.value);
    } catch (error) {
        console.log(error)
    }
}

const handleImageChange = (e) => {
    const file = e.target.files[0];
    upload(file);
}

const removeImage = () => {
    image.value = null
    emits('update:modelValue', image.value);
}

onMounted(()=> {
    image.value = props.modelValue
    emits('update:modelValue',image.value)
})

</script>