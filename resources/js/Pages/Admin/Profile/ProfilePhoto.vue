<template>
    <div class="card mb-3">
        <div class="card-header">{{ $t('profile_photo') }}</div>
        <div class="card-body py-4">
            <Alert v-if='form.errors.photo' type='danger'>{{ form.errors.photo }}
            </Alert>
            <div class="text-center">
                <div class="mb-4">
                    <img v-if='form.photo' :src="form.photo" :alt="form.name" class="rounded-circle"
                        width="150" height="150" style="object-fit: cover;">
                    <DefaultAvatar class="mx-auto" v-else :size='150'>
                        <h1 class='display-1'><b>{{ user.name[0].toUpperCase() }}</b></h1>
                    </DefaultAvatar>
                </div>
                <div>
                    <input type="file" class="d-none" accept="image/*" ref='photoInput' @change="update">
                    <template v-if="form.photo">
                        <button class="btn btn-light btn-sm me-2" @click="$refs.photoInput.click()">{{
                            $t('change_photo') }}</button>
                        <button class="btn btn-danger btn-sm " @click="remove">{{ $t('remove_photo')
                        }}</button>
                    </template>
                    <template v-else>
                        <button class="btn btn-primary btn-sm me-2" @click="$refs.photoInput.click()">{{
                            $t('add_profile_photo') }}</button>
                    </template>

                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Alert from '@/Components/Alert.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { useToast } from 'vue-toastification'
import DefaultAvatar from '@/Components/DefaultAvatar.vue';

const user = usePage().props.auth.user;
const toast = useToast();


const form = useForm({ photo: user.photo });

const uploadPhoto = async (file) => {
    try {
        const formData = new FormData();
        formData.append('image', file);
        const response = await axios.post(route('admin.images.upload'), formData);
        return response.data.url;
    } catch (error) {
        const { response } = error;
        if (response.status == 422) {
            toast.error(response.data.message);
        }
        throw error
    }
}

const update = async (e) => {
    try {
        const file = e.target.files[0];
        const photoUrl = await uploadPhoto(file);
        form.photo = photoUrl;
        form.post(route('admin.profile.updatePhoto'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess(page) {
                toast.success(page.props.success)
            }
        });
    } catch (error) {
        console.log(error)
    }

}

const remove = () => {
    form.photo = null
    form.post(route('admin.profile.updatePhoto'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess(page) {
            toast.success(page.props.success)
        }
    });
}

</script>