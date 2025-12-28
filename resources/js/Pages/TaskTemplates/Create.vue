<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    projectTypes: Array,
});

const form = useForm({
    project_type_id: '',
    name: '',
    description: '',
    default_deadline_days: 3,
    order: 1,
});

const submit = () => {
    form.post(route('task-templates.store'));
};
</script>

<template>
    <Head title="Nuovo Template Task" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('task-templates.index')">Template Task</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Nuovo</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Nuovo Template Task</h1>
            <p class="subtitle">Questo template verrà usato per generare task automaticamente</p>

            <div class="columns">
                <div class="column is-8">
                    <div class="box">
                        <form @submit.prevent="submit">
                            <div class="field">
                                <label class="label">Tipo Progetto *</label>
                                <div class="control">
                                    <div class="select is-fullwidth" :class="{ 'is-danger': form.errors.project_type_id }">
                                        <select v-model="form.project_type_id" required>
                                            <option value="">Seleziona un tipo di progetto</option>
                                            <option v-for="type in projectTypes" :key="type.id" :value="type.id">
                                                {{ type.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.project_type_id" class="help is-danger">{{ form.errors.project_type_id }}</p>
                                <p class="help">Per quale tipo di progetto è questo template?</p>
                            </div>

                            <div class="field">
                                <label class="label">Nome Task *</label>
                                <div class="control">
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="input"
                                        :class="{ 'is-danger': form.errors.name }"
                                        placeholder="es: Analisi Requisiti, Design, Sviluppo..."
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
                                        rows="3"
                                        placeholder="Descrivi cosa comporta questa task..."
                                    ></textarea>
                                </div>
                                <p v-if="form.errors.description" class="help is-danger">{{ form.errors.description }}</p>
                            </div>

                            <div class="field">
                                <label class="label">Scadenza Default (giorni) *</label>
                                <div class="control">
                                    <input
                                        v-model.number="form.default_deadline_days"
                                        type="number"
                                        class="input"
                                        :class="{ 'is-danger': form.errors.default_deadline_days }"
                                        min="1"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.default_deadline_days" class="help is-danger">{{ form.errors.default_deadline_days }}</p>
                                <p class="help">Quanti giorni dalla creazione del progetto dovrebbe scadere questa task?</p>
                            </div>

                            <div class="field">
                                <label class="label">Ordine di Esecuzione *</label>
                                <div class="control">
                                    <input
                                        v-model.number="form.order"
                                        type="number"
                                        class="input"
                                        :class="{ 'is-danger': form.errors.order }"
                                        min="0"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.order" class="help is-danger">{{ form.errors.order }}</p>
                                <p class="help">In quale ordine va eseguita? (0 = prima, 1 = seconda, ecc.)</p>
                            </div>

                            <article class="message is-info">
                                <div class="message-body">
                                    <strong>Come funziona:</strong> Quando viene creato un progetto del tipo selezionato, verrà generata automaticamente una task con questi parametri. La scadenza sarà calcolata come "data creazione progetto + giorni specificati".
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
                                        Crea Template
                                    </button>
                                </div>
                                <div class="control">
                                    <Link :href="route('task-templates.index')" class="button is-light">
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

