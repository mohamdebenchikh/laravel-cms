<template>
     <div class="card">
                    <div class="card-header">{{ $t('change_password') }}</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="current_password" class='form-label'>{{ $t('current_password') }}</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                v-model='form.current_password'
                                :class="[form.errors.current_password ? 'is-invalid' : '']">
                            <span v-if="form.errors.current_password" class="invalid-feedback">{{
                                form.errors.current_password }}</span>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class='form-label'>{{ $t('new_password') }}</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                v-model='form.new_password'
                                :class="[form.errors.new_password ? 'is-invalid' : '']">
                            <span v-if="form.errors.new_password" class="invalid-feedback">{{
                                form.errors.new_password }}</span>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class='form-label'>{{ $t('confirm_new_password')
                            }}</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" v-model='form.new_password_confirmation'
                                :class="[form.errors.new_password_confirmation ? 'is-invalid' : '']">
                            <span v-if="form.errors.new_password_confirmation" class="invalid-feedback">{{
                                form.errors.new_password_confirmation }}</span>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary btn-sm" @click="changePassword">{{ $t('change_password') }}</button>
                    </div>
                </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification'

const toast = useToast();

const form = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
});

const changePassword = () => {
    form.post(route('admin.profile.changePassword'),{
        preserveScroll:true,
        preserveState:true,
        onSuccess(page){
            toast.success(page.props.success);
            form.reset()
        }
    })
}


</script>