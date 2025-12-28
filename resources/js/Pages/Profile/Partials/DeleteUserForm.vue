<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header class="mb-5">
            <h2 class="title is-4">Elimina Account</h2>
            <p class="subtitle is-6">
                Una volta eliminato il tuo account, tutte le sue risorse e dati saranno eliminati permanentemente.
                Prima di eliminare il tuo account, scarica tutti i dati o le informazioni che desideri conservare.
            </p>
        </header>

        <button @click="confirmUserDeletion" class="button is-danger">
            Elimina Account
        </button>

        <!-- Modal Bulma -->
        <div class="modal" :class="{ 'is-active': confirmingUserDeletion }">
            <div class="modal-background" @click="closeModal"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Sei sicuro di voler eliminare il tuo account?</p>
                    <button class="delete" aria-label="close" @click="closeModal"></button>
                </header>
                <section class="modal-card-body">
                    <p class="mb-4">
                        Una volta eliminato il tuo account, tutte le sue risorse e dati saranno eliminati permanentemente.
                        Inserisci la tua password per confermare che desideri eliminare definitivamente il tuo account.
                    </p>

                    <div class="field">
                        <label class="label is-sr-only" for="password">Password</label>
                        <div class="control">
                            <input
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="input"
                                :class="{ 'is-danger': form.errors.password }"
                                placeholder="Password"
                                @keyup.enter="deleteUser"
                            />
                        </div>
                        <p v-if="form.errors.password" class="help is-danger">{{ form.errors.password }}</p>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <div class="buttons">
                        <button type="button" @click="closeModal" class="button">
                            Annulla
                        </button>
                        <button
                            type="button"
                            @click="deleteUser"
                            class="button is-danger"
                            :class="{ 'is-loading': form.processing }"
                            :disabled="form.processing"
                        >
                            Elimina Account
                        </button>
                    </div>
                </footer>
            </div>
        </div>
    </section>
</template>
