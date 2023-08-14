<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('dashboard') }} | {{ $t('create_new_page') }}</title>
        </template>
        <div class="mb-4">
            <h3>{{ $t('create_new_page') }}</h3>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ $t('page_title') }}</label>
                            <input type="text" :placeholder="$t('title')" v-model='form.title' name="title" id="title"
                                class="form-control" :class="[form.errors.title ? 'is-invalid' : '']">
                            <span v-if="form.errors.title" class='invalid-feedback'>{{ form.errors.title }}</span>
                        </div>
                        <div class="mb-3">

                            <label class="form-label" for="content">{{ $t('content') }}</label>
                            <div v-if="form.errors.content" class='mb-2'>
                                <Alert type='danger'>{{ form.errors.content }}</Alert>
                            </div>
                            <QuillEditor id='content' content-type="html" toolbar='full' theme="snow"
                                v-model:content='form.content'></QuillEditor>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ $t('description') }}</label>
                            <textarea v-model='form.description' :placeholder="$t('description')" name="description"
                                id="description" class="form-control"
                                :class="[form.errors.description ? 'is-invalid' : '']"></textarea>
                            <span v-if="form.errors.description" class='invalid-feedback'>{{ form.errors.description
                            }}</span>
                        </div>

                    </div>
                </div>


            </div>
            <div class="col-md-4">

                <div class="card mb-3">
                    <div class="card-header">{{ $t('page_cover') }}</div>
                    <div class="card-body">
                        <ImageUpload v-model="form.cover"></ImageUpload>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">


                        <div class="mb-3">
                            <label for="tags" class="form-label">{{ $t('keywords') }}</label>
                            <VueMultiselect v-model="keywords" :options='[]' @tag='addKeyword' :multiple="true" :taggable="true"
                                :tag-placeholder="$t('enter_keywords')" :placeholder="$t('enter_keywords')">
                            </VueMultiselect>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ $t('status') }}</label>
                            <select name="status" id="status" v-model='form.status'
                                :class="[form.errors.status ? 'is-invalid' : '']" class="form-control">
                                <option :value="false">{{ $t('draft') }}</option>
                                <option :value="true">{{ $t('publish') }}</option>
                            </select>
                            <span class='invalid-feedback' v-if='form.errors.status'>{{ form.errors.status }}</span>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary" @click='submit'>{{ $t('save_page') }}</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import ImageUpload from '@/Components/ImageUpload.vue';
import Alert from '@/Components/Alert.vue';
import { useForm } from '@inertiajs/vue3';
import VueMultiselect from 'vue-multiselect'
import { ref } from 'vue';

const keywords = ref([])
const props = defineProps({
    categories: Array,
    tags: Array
})

const addKeyword = (keyword) => {
    keywords.value.push(keyword)
}

const form = useForm({
    title: '',
    content: '',
    cover: '',
    status: false,
    description: '',
    keywords:[]
})



const submit = () => {

    form.keywords = keywords.value.join(',');
    form.post(route('admin.pages.store'));
}

</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
