<template>
    <div class="card mb-3">
        <div class="card-header">{{ $t('personal_information') }}</div>
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">{{ $t('name') }}</label>
                <input type="text" name="name" id="name" class="form-control" :placeholder="$t('name')" v-model="form.name"
                    :class="[form.errors.name ? 'is-invalid' : '']">
                <span class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ $t('email_address') }}</label>
                <input type="text" name="email" id="email" class="form-control" :placeholder="$t('email')"
                    v-model="form.email" :class="[form.errors.email ? 'is-invalid' : '']">
                <span class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}</span>
            </div>
            <div class="mb-3">
                <label for="about" class="form-label">{{ $t('about') }}</label>
                <textarea name="about" id="about" class="form-control" rows="3" :placeholder="$t('about')"
                    v-model="form.about" :class="[form.errors.about ? 'is-invalid' : '']"></textarea>
                <span class="invalid-feedback" v-if="form.errors.about">{{ form.errors.about }}</span>
            </div>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-secondary btn-sm" @click="save">{{ $t('save_change') }}</button>
        </div>
    </div>
</template>
<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification'

const user = usePage().props.auth.user;
const toast = useToast();


const form = useForm({
    name: user.name,
    email: user.email,
    about: user.about
});

const save = () => {
    form.post(route('admin.profile.updatePersonalInfo'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess(page){
            toast.success(page.props.success);
        }
    })
}


</script>