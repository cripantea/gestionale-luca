<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array,
});

const form = useForm({
    project_id: '',
    name: '',
    description: '',
    deadline: '',
    blocked_by_task_id: null,
});

const submit = () => {
    form.post(route('tasks.store'));
};

// Calcola data di default (3 giorni da oggi)
const defaultDeadline = () => {
    const date = new Date();
    date.setDate(date.getDate() + 3);
    return date.toISOString().slice(0, 16);
};

if (!form.deadline) {
    form.deadline = defaultDeadline();
}
</script>

<template>
    <Head title="Nuova Task" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('tasks.index')">Task</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Nuova</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Nuova Task</h1>

            <div class="columns">
                <div class="column is-8">
                    <div class="box">
                        <form @submit.prevent="submit">
                            <div class="field">
                                <label class="label">Progetto *</label>
                                <div class="control">
                                    <div class="select is-fullwidth" :class="{ 'is-danger': form.errors.project_id }">
                                        <select v-model="form.project_id" required>
                                            <option value="">Seleziona un progetto</option>
                                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                                {{ project.name }} - {{ project.contact.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.project_id" class="help is-danger">{{ form.errors.project_id }}</p>
                            </div>

                            <div class="field">
                                <label class="label">Nome Task *</label>
                                <div class="control">
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="input"
                                        :class="{ 'is-danger': form.errors.name }"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.name" class="help is-danger">{{ form.errors.name }}</p>
                            </div>

                            <div class="field">
                                <label class="label">Scadenza *</label>
                                <div class="control">
                                    <input
                                        v-model="form.deadline"
                                        type="datetime-local"
                                        class="input"
                                        :class="{ 'is-danger': form.errors.deadline }"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.deadline" class="help is-danger">{{ form.errors.deadline }}</p>
                                <p class="help">Default: 3 giorni da oggi</p>
                            </div>

                            <div class="field">
                                <label class="label">Descrizione</label>
                                <div class="control">
                                    <textarea
                                        v-model="form.description"
                                        class="textarea"
                                        :class="{ 'is-danger': form.errors.description }"
                                        rows="4"
                                    ></textarea>
                                </div>
                                <p v-if="form.errors.description" class="help is-danger">{{ form.errors.description }}</p>
                            </div>

                            <article class="message is-info">
                                <div class="message-body">
                                    <strong>Nota:</strong> La task verrà creata con stato "Da Fare". Potrai gestire gli stati e le dipendenze dalla pagina di dettaglio.
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
                                        Crea Task
                                    </button>
                                </div>
                                <div class="control">
                                    <Link :href="route('tasks.index')" class="button is-light">
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

