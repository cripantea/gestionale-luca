<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profilo" />

    <AuthenticatedLayout>
        <div class="content">
            <h1 class="title is-2">Profilo</h1>
            <p class="subtitle">Gestisci le informazioni del tuo account</p>

            <div class="columns is-multiline">
                <div class="column is-12">
                    <div class="box">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>
                </div>

            <div class="column is-12">
                <div class="box">
                    <UpdatePasswordForm />
                </div>
            </div>

            <div class="column is-12">
                <div class="box">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>
