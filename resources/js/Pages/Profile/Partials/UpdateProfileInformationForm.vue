<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-5">
            <h2 class="title is-4">Informazioni Profilo</h2>
            <p class="subtitle is-6">Aggiorna le informazioni del tuo account e l'indirizzo email.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))">
            <div class="field">
                <label class="label" for="name">Nome</label>
                <div class="control">
                    <input
                        id="name"
                        type="text"
                        class="input"
                        :class="{ 'is-danger': form.errors.name }"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"

                    />
                </div>
                <p v-if="form.errors.name" class="help is-danger">{{ form.errors.name }}</p>
            </div>

            <div class="field">
                <label class="label" for="email">Email</label>
                <div class="control">
                    <input
                        id="email"
                        type="email"
                        class="input"
                        :class="{ 'is-danger': form.errors.email }"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                </div>
                <p v-if="form.errors.email" class="help is-danger">{{ form.errors.email }}</p>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="notification is-warning">
                <p>
                    Il tuo indirizzo email non è verificato.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="button is-small is-warning is-light ml-2"
                    >
                        Clicca qui per inviare nuovamente l'email di verifica.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="notification is-success is-light mt-3">
                    Un nuovo link di verifica è stato inviato al tuo indirizzo email.
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button type="submit" class="button is-primary" :class="{ 'is-loading': form.processing }" :disabled="form.processing">
                        Salva
                    </button>
                </div>
                <div class="control">
                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.recentlySuccessful" class="help is-success">
                            Salvato.
                        </p>
                    </Transition>
                </div>
            </div>
        </form>
    </section>
</template>
