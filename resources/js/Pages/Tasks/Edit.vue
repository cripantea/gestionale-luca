<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    task: Object,
    projects: Array,
    availableTasks: Array,
});

const form = useForm({
    project_id: props.task.project_id,
    name: props.task.name,
    description: props.task.description,
    deadline: props.task.deadline ? new Date(props.task.deadline).toISOString().slice(0, 16) : '',
    status: props.task.status,
    blocked_by_task_id: props.task.blocked_by_task_id,
});

const submit = () => {
    form.put(route('tasks.update', props.task.id));
};
</script>

<template>
    <Head :title="`Modifica ${task.name}`" />

    <AuthenticatedLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('tasks.index')">Task</Link></li>
                    <li><Link :href="route('tasks.show', task.id)">{{ task.name }}</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Modifica</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Modifica Task</h1>

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
                            </div>

                            <div class="field">
                                <label class="label">Stato</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select v-model="form.status">
                                            <option value="da_fare">Da Fare</option>
                                            <option value="in_corso">In Corso</option>
                                            <option value="completato">Completato</option>
                                            <option value="in_pausa">In Pausa</option>
                                            <option value="in_attesa">In Attesa</option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.status" class="help is-danger">{{ form.errors.status }}</p>
                                <p class="help">Puoi anche gestire lo stato dalla pagina di dettaglio con i bottoni azione</p>
                            </div>

                            <div class="field">
                                <label class="label">Task Bloccante</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select v-model="form.blocked_by_task_id">
                                            <option :value="null">Nessuna dipendenza</option>
                                            <option v-for="availableTask in availableTasks" :key="availableTask.id" :value="availableTask.id">
                                                {{ availableTask.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.blocked_by_task_id" class="help is-danger">{{ form.errors.blocked_by_task_id }}</p>
                                <p class="help">Seleziona una task che deve essere completata prima di questa</p>
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

                            <div class="field is-grouped">
                                <div class="control">
                                    <button
                                        type="submit"
                                        class="button is-primary"
                                        :class="{ 'is-loading': form.processing }"
                                        :disabled="form.processing"
                                    >
                                        Aggiorna
                                    </button>
                                </div>
                                <div class="control">
                                    <Link :href="route('tasks.show', task.id)" class="button is-light">
                                        Annulla
                                    </Link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

