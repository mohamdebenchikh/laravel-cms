<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('dashboard') + ' | ' +  $t('edit')  }}</title>
        </template>
        <div class="mb-4">
            <h3>{{ $t('edit') }}</h3>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ $t('post_title') }}</label>
                            <input type="text" :placeholder="$t('title')" v-model='form.title' name="title" id="title"
                                class="form-control" :class="[form.errors.title ? 'is-invalid' : '']">
                            <span v-if="form.errors.title" class='invalid-feedback'>{{ form.errors.title }}</span>
                        </div>
                        <div class="mb-3">

                            <label class="form-label" for="content">{{ $t('content') }}</label>
                            <div v-if="form.errors.content" class='mb-2'>
                                <Alert type='danger'>{{ form.errors.content }}</Alert>
                            </div>
                            <QuillEditor id='content' content-type="html" toolbar='essential' theme="snow"
                                v-model:content='form.content'></QuillEditor>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">{{ $t('excerpt') }}</label>
                            <textarea v-model='form.excerpt' :placeholder="$t('excerpt')" name="excerpt" id="excerpt"
                                class="form-control" :class="[form.errors.excerpt ? 'is-invalid' : '']"></textarea>
                            <span v-if="form.errors.excerpt" class='invalid-feedback'>{{ form.errors.excerpt }}</span>
                        </div>

                    </div>
                </div>


            </div>
            <div class="col-md-4">

                <div class="card mb-3">
                    <div class="card-header">{{ $t('post_image') }}</div>
                    <div class="card-body">
                        <ImageUpload v-model="form.image"></ImageUpload>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category" class="form-label">{{ $t('category') }}</label>
                            <select name="category" id="category" :class="[form.errors.category_id ? 'is-invalid' : '']"
                                class="form-control" v-model="form.category_id">
                                <option v-for="category in categories" :selected="post.category_id == category.id" :key="category.id" :value="category.id">{{
                                    category.name }}</option>
                            </select>
                            <span class="invalid-feedback" v-if='form.errors.category_id'>{{ form.errors.category_id
                            }}</span>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">{{ $t('tags') }}</label>
                            <VueMultiselect v-model="selectedTags" @tag='addNewTag' :searchable='true' :options='tags' label="name"
                                track-by='id' :multiple="true" :taggable="true" :tag-placeholder="$t('add_new_tag')"
                                :placeholder="$t('select_post_tags')">
                            </VueMultiselect>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ $t('status') }}</label>
                            <select name="status" id="status" v-model='form.status'
                                :class="[form.errors.status ? 'is-invalid' : '']" class="form-control">
                                <option :value="0" >{{ $t('draft') }}</option>
                                <option :value="1" >{{ $t('publish') }}</option>
                            </select>
                            <span class='invalid-feedback' v-if='form.errors.status'>{{ form.errors.status }}</span>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" v-model='form.feature' id="feature">
                            <label class="form-check-label" for="feature">
                                {{ $t('feature') }}
                            </label>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-success" @click='submit'>{{ $t('save_post') }}</button>
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
import { useToast } from 'vue-toastification'
import { onMounted, ref } from 'vue';

const toast = useToast();
const selectedTags = ref()
const props = defineProps({
    categories: Array,
    post:Object,
    tags: Array
})

const addNewTag = (newTag) => {
    const tagForm = useForm({ name: newTag });
    tagForm.post(route('admin.tags.store'), {
        preserveState: true,
        preserveScroll: true,
        replace: false,
        onSuccess(page) {
            toast.success(page.props.success);
        }
    })
}

const form = useForm({
    title:  props.post.title ?? '',
    content: props.post.content ?? '',
    image: props.post.image ?? '',
    category_id: props.post.category_id ?? '',
    tags: props.post.tags ?? [],
    status: props.post.status ?? false,
    feature: props.post.feature == 1 ? true : false,
    excerpt: props.post.excerpt ?? ''
})


const submit = () => {
    const tagsIds = selectedTags.value.map(tag => tag.id);
    form.tags = tagsIds;
    form.put(route('admin.posts.update',props.post.id));
}

onMounted(()=> {
    selectedTags.value = props.post.tags
})

</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
