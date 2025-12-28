<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    taskTemplate: Object,
    projectTypes: Array,
});

const form = useForm({
    project_type_id: props.taskTemplate.project_type_id,
    name: props.taskTemplate.name,
    description: props.taskTemplate.description,
    default_deadline_days: props.taskTemplate.default_deadline_days,
    order: props.taskTemplate.order,
});

const submit = () => {
    form.put(route('task-templates.update', props.taskTemplate.id));
};
</script>

<template>
    <Head :title="`Modifica ${taskTemplate.name}`" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('task-templates.index')">Template Task</Link></li>
                    <li><Link :href="route('task-templates.show', taskTemplate.id)">{{ taskTemplate.name }}</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Modifica</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Modifica Template Task</h1>

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
                                <label class="label">Descrizione</label>
                                <div class="control">
                                    <textarea
                                        v-model="form.description"
                                        class="textarea"
                                        :class="{ 'is-danger': form.errors.description }"
                                        rows="3"
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
                                    <Link :href="route('task-templates.show', taskTemplate.id)" class="button is-light">
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

