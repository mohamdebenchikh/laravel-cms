<template>
    <AuthLayout>

        <Head>
            <title>{{ $t('admin_login') }}</title>
        </Head>
        <div class="card shadow" style="min-width: 400px;">
            <div class="card-body p-4">
                <div class="text-center p-2">
                    <h4>{{ $t('login') }}</h4>
                </div>
                <form @submit.prevent='login'>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{$t('email')}}</label>
                        <input type="email" v-model="form.email" required :class="[form.errors.email ? 'is-invalid' : '']"
                            :placeholder="$t('email_address')" class="form-control" id="email" name='email'>
                        <span v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ $t('password') }}</label>
                        <input type="password" v-model="form.password" required :placeholder="$t('password')"
                            :class="[form.errors.password ? 'is-invalid' : '']" class="form-control" id="password"
                            name='password'>
                        <span v-if="form.errors.password" class="invalid-feedback">{{ form.errors.password }}</span>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" v-model="form.remember" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">
                            {{ $t('remember_me') }}
                        </label>
                    </div>
                    <button class="btn btn-primary w-100">{{  $t('login')  }}</button>
                    <div class="mt-2">
                        <a href="#">{{ $t('forgot_password') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>
<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const login = () => {
    form.post(route('admin.login.authenticate'));
}


</script>