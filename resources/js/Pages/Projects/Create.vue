<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    contacts: Array,
    projectTypes: Array,
});

const form = useForm({
    contact_id: '',
    project_type_id: '',
    name: '',
    description: '',
    status: 'attivo',
});

const submit = () => {
    form.post(route('projects.store'));
};
</script>

<template>
    <Head title="Nuovo Progetto" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('projects.index')">Progetti</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">Nuovo</a></li>
                </ul>
            </nav>

            <h1 class="title is-2">Nuovo Progetto</h1>
            <p class="subtitle">Le task verranno generate automaticamente dal template</p>

            <div class="columns">
                <div class="column is-8">
                    <div class="box">
                        <form @submit.prevent="submit">
                            <div class="field">
                                <label class="label">Nome Progetto *</label>
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
                                <label class="label">Cliente *</label>
                                <div class="control">
                                    <div class="select is-fullwidth" :class="{ 'is-danger': form.errors.contact_id }">
                                        <select v-model="form.contact_id" required>
                                            <option value="">Seleziona un cliente</option>
                                            <option v-for="contact in contacts" :key="contact.id" :value="contact.id">
                                                {{ contact.name }} {{ contact.company ? `(${contact.company})` : '' }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.contact_id" class="help is-danger">{{ form.errors.contact_id }}</p>
                                <p class="help">Solo i clienti sono disponibili</p>
                            </div>

                            <div class="field">
                                <label class="label">Tipo Progetto *</label>
                                <div class="control">
                                    <div class="select is-fullwidth" :class="{ 'is-danger': form.errors.project_type_id }">
                                        <select v-model="form.project_type_id" required>
                                            <option value="">Seleziona un tipo</option>
                                            <option v-for="type in projectTypes" :key="type.id" :value="type.id">
                                                {{ type.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.project_type_id" class="help is-danger">{{ form.errors.project_type_id }}</p>
                            </div>

                            <div class="field">
                                <label class="label">Stato</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select v-model="form.status">
                                            <option value="attivo">Attivo</option>
                                            <option value="completato">Completato</option>
                                            <option value="sospeso">Sospeso</option>
                                        </select>
                                    </div>
                                </div>
                                <p v-if="form.errors.status" class="help is-danger">{{ form.errors.status }}</p>
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
                                    <strong>Nota:</strong> Quando salvi il progetto, verranno create automaticamente tutte le task del template associato al tipo di progetto selezionato.
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
                                        Crea Progetto
                                    </button>
                                </div>
                                <div class="control">
                                    <Link :href="route('projects.index')" class="button is-light">
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

