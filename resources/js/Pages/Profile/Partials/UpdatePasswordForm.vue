<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-5">
            <h2 class="title is-4">Aggiorna Password</h2>
            <p class="subtitle is-6">Assicurati che il tuo account utilizzi una password lunga e casuale per rimanere sicuro.</p>
        </header>

        <form @submit.prevent="updatePassword">
            <div class="field">
                <label class="label" for="current_password">Password Attuale</label>
                <div class="control">
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="input"
                        :class="{ 'is-danger': form.errors.current_password }"
                        autocomplete="current-password"
                    />
                </div>
                <p v-if="form.errors.current_password" class="help is-danger">{{ form.errors.current_password }}</p>
            </div>

            <div class="field">
                <label class="label" for="password">Nuova Password</label>
                <div class="control">
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="input"
                        :class="{ 'is-danger': form.errors.password }"
                        autocomplete="new-password"
                    />
                </div>
                <p v-if="form.errors.password" class="help is-danger">{{ form.errors.password }}</p>
            </div>

            <div class="field">
                <label class="label" for="password_confirmation">Conferma Password</label>
                <div class="control">
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="input"
                        :class="{ 'is-danger': form.errors.password_confirmation }"
                        autocomplete="new-password"
                    />
                </div>
                <p v-if="form.errors.password_confirmation" class="help is-danger">{{ form.errors.password_confirmation }}</p>
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
