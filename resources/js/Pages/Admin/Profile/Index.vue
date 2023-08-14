<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('dashboard') }} | {{ $t('profile') }}</title>
        </template>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="my-4">
                                <img v-if='user.photo' :src="user.photo" :alt="user.name" class="rounded-circle mb-2"
                                    width="150" height="150" style="object-fit: cover;border:#eee 2px solid">

                                <DefaultAvatar class="mx-auto" v-else :size='150'>
                                    <h1 class='display-1'><b>{{ user.name[0].toUpperCase() }}</b></h1>
                                </DefaultAvatar>

                                <h3 class="card-title">{{ user.name }}</h3>
                                <span class="text-muted">{{ user.email }}</span>
                                <hr />
                                <p>{{ user.about }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <ProfilePhoto></ProfilePhoto>
                <PersonalInformation></PersonalInformation>
                <ChangePassword></ChangePassword>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';
import DefaultAvatar from '@/Components/DefaultAvatar.vue';
import ProfilePhoto from './ProfilePhoto.vue';
import PersonalInformation from './PersonalInformation.vue';
import ChangePassword from './ChangePassword.vue';

const user = usePage().props.auth.user;


</script>