<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    taskTemplate: Object,
});
</script>

<template>
    <Head :title="taskTemplate.name" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('task-templates.index')">Template Task</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">{{ taskTemplate.name }}</a></li>
                </ul>
            </nav>

            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <h1 class="title is-2">{{ taskTemplate.name }}</h1>
                            <p class="subtitle">
                                <Link :href="route('project-types.show', taskTemplate.project_type.id)">
                                    <span class="tag is-info is-medium">{{ taskTemplate.project_type.name }}</span>
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <div class="buttons">
                            <Link :href="route('task-templates.edit', taskTemplate.id)" class="button is-warning">
                                <span class="icon"><i class="fas fa-edit"></i></span>
                                <span>Modifica</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column is-6">
                    <div class="box">
                        <h2 class="title is-4">Dettagli Template</h2>
                        <div class="field">
                            <label class="label">Nome Task</label>
                            <p>{{ taskTemplate.name }}</p>
                        </div>
                        <div class="field" v-if="taskTemplate.description">
                            <label class="label">Descrizione</label>
                            <p>{{ taskTemplate.description }}</p>
                        </div>
                        <div class="field">
                            <label class="label">Tipo Progetto</label>
                            <p>
                                <Link :href="route('project-types.show', taskTemplate.project_type.id)">
                                    <span class="tag is-info">{{ taskTemplate.project_type.name }}</span>
                                </Link>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label">Scadenza Default</label>
                            <p>
                                <span class="tag is-light">
                                    {{ taskTemplate.default_deadline_days }} giorn{{ taskTemplate.default_deadline_days === 1 ? 'o' : 'i' }} dalla creazione
                                </span>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label">Ordine di Esecuzione</label>
                            <p><span class="tag is-dark">#{{ taskTemplate.order }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="column is-6">
                    <div class="box">
                        <h2 class="title is-4">Utilizzo</h2>
                        <article class="message is-info">
                            <div class="message-body">
                                <p class="mb-3">
                                    Questo template viene usato per generare automaticamente una task quando viene creato un progetto di tipo
                                    <strong>{{ taskTemplate.project_type.name }}</strong>.
                                </p>
                                <p>
                                    La task generata avrà:
                                </p>
                                <ul class="mt-2">
                                    <li>• Nome: <strong>{{ taskTemplate.name }}</strong></li>
                                    <li>• Descrizione: {{ taskTemplate.description || '(nessuna)' }}</li>
                                    <li>• Scadenza: {{ taskTemplate.default_deadline_days }} giorni dalla creazione del progetto</li>
                                    <li>• Ordine: {{ taskTemplate.order }}° nella lista delle task</li>
                                </ul>
                            </div>
                        </article>
                    </div>

                    <div class="box">
                        <h2 class="title is-5">Altri Template di {{ taskTemplate.project_type.name }}</h2>
                        <p class="mb-3 is-size-7 has-text-grey">
                            Tutti i template task per questo tipo di progetto:
                        </p>
                        <Link :href="route('project-types.show', taskTemplate.project_type.id)" class="button is-small is-info">
                            <span class="icon"><i class="fas fa-list"></i></span>
                            <span>Vedi tutti i template</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

