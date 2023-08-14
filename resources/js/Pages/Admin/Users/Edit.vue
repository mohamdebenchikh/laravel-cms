<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('dashboard') }} | {{ $t('edit') }}</title>
        </template>
        <form @submit.prevent='submit'>
            <div class="mb-4">
                <h3>{{ $t('edit') }}</h3>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">{{ $t('personal_information') }}</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ $t('name') }}</label>
                                <input type="text" v-model="form.name" class="form-control"
                                    :class="[form.errors.name ? 'is-invalid' : '']" id="name">
                                <span v-if='form.errors.name' class="invalid-feedback">{{ form.errors.name }}</span>
                            </div>
                            <div class="mb-3">
                                <label for="about" class="form-label">{{ $t('about') }}</label>
                                <textarea v-model="form.about" class="form-control"
                                    :class="[form.errors.about ? 'is-invalid' : '']" id="about"></textarea>
                                <span v-if='form.errors.about' class="invalid-feedback">{{ form.errors.about }}</span>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ $t('email') }}</label>
                                <input type="email" v-model="form.email" class="form-control"
                                    :class="[form.errors.email ? 'is-invalid' : '']" id="email">
                                <span v-if='form.errors.email' class="invalid-feedback">{{ form.errors.email }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ $t('password') }}</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ $t('password') }}</label>
                                <input type="password" v-model="form.password" class="form-control"
                                    :class="[form.errors.password ? 'is-invalid' : '']" id="password">
                                <span v-if='form.errors.password' class="invalid-feedback">{{ form.errors.password }}</span>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ $t('confirm_password') }}</label>
                                <input type="password" v-model="form.password_confirmation" class="form-control"
                                    :class="[form.errors.password_confirmation ? 'is-invalid' : '']"
                                    id="password_confirmation">
                                <span v-if='form.errors.password_confirmation' class="invalid-feedback">{{
                                    form.errors.password_confirmation }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">{{ $t('user_photo') }}</div>
                        <div class="card-body">
                            <div v-if="form.photo" class="mb-3">
                                <img :src="form.photo" class="w-100 rounded mb-3">
                                <button type="button" class="btn btn-danger w-100" @click="removePhoto">{{
                                    $t('remove_image') }}</button>
                            </div>
                            <div>
                                <input class="form-control" @change="handlePhotoChange" accept="image/*" type="file"
                                    id="photo">
                            </div>
                        </div>
                    </div>
                    <div class="text-end py-2">
                        <button class="btn btn-success" type="submit">{{ $t('save') }}</button>
                    </div>
                </div>
            </div>

        </form>

    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const user = usePage().props.user;

const form = useForm({
    name: user.name,
    photo: user.photo,
    about: user.about,
    email: user.email,
    password: '',
    password_confirmation: '',
});


const handlePhotoChange = (e) => {
    const file = e.target.files[0];

    const formData = new FormData();
    formData.append('image', file);
    axios.post(route('admin.images.upload'), formData)
        .then(response => {
            form.photo = response.data.url;
        }).catch(error => {
            console.log(error)
        })
}

const submit = () => {
    form.put(route('admin.users.update', user.id));
}

const removePhoto = () => {
    form.photo = null
}

</script>