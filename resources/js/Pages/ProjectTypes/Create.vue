<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('project-types.store'));
};
</script>

<template>
    <Head title="Nuovo Tipo Progetto" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('project-types.index')">Tipi Progetto</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Nuovo</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Nuovo Tipo Progetto</h1>
            <p class="subtitle">Dopo aver creato il tipo, potrai aggiungere i template task</p>

            <div class="columns">
                <div class="column is-8">
                    <div class="box">
                        <form @submit.prevent="submit">
                            <div class="field">
                                <label class="label">Nome *</label>
                                <div class="control">
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="input"
                                        :class="{ 'is-danger': form.errors.name }"
                                        placeholder="es: Sviluppo Sito Web, SEO, Marketing..."
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.name" class="help is-danger">{{ form.errors.name }}</p>
                            </div>

                            <div class="field">
                                <label class="label">Descrizione</label>
                                <div class="control">
                                    <textarea
                                        v-model="form.description"
                                        class="textarea"
                                        :class="{ 'is-danger': form.errors.description }"
                                        rows="4"
                                        placeholder="Descrivi questo tipo di progetto..."
                                    ></textarea>
                                </div>
                                <p v-if="form.errors.description" class="help is-danger">{{ form.errors.description }}</p>
                            </div>

                            <article class="message is-info">
                                <div class="message-body">
                                    <strong>Prossimo passo:</strong> Dopo aver creato questo tipo di progetto, dovrai configurare i template task che verranno generati automaticamente quando si crea un progetto di questo tipo.
                                </div>
                            </article>

                            <div class="field is-grouped">
                                <div class="control">
                                    <button
                                        type="submit"
                                        class="button is-primary"
                                        :class="{ 'is-loading': form.processing }"
                                        :disabled="form.processing"
                                    >
                                        Crea Tipo Progetto
                                    </button>
                                </div>
                                <div class="control">
                                    <Link :href="route('project-types.index')" class="button is-light">
                                        Annulla
                                    </Link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

