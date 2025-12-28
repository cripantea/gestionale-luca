<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    projectType: Object,
});

const form = useForm({
    name: props.projectType.name,
    description: props.projectType.description,
});

const submit = () => {
    form.put(route('project-types.update', props.projectType.id));
};
</script>

<template>
    <Head :title="`Modifica ${projectType.name}`" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('project-types.index')">Tipi Progetto</Link></li>
                    <li><Link :href="route('project-types.show', projectType.id)">{{ projectType.name }}</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Modifica</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Modifica Tipo Progetto</h1>

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
                                    <Link :href="route('project-types.show', projectType.id)" class="button is-light">
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

