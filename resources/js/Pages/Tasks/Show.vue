<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, router } from '@inertiajs/vue3';

const props = defineProps({
    task: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatWorkTime = (minutes) => {
    if (!minutes) return '0 minuti';
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    if (hours > 0) {
        return `${hours}h ${mins}m`;
    }
    return `${mins}m`;
};

const getStatusClass = (status) => {
    const classes = {
        'da_fare': 'is-info',
        'in_corso': 'is-warning',
        'completato': 'is-success',
        'in_pausa': 'is-light',
        'in_attesa': 'is-light',
    };
    return classes[status] || 'is-light';
};

const startTask = () => {
    router.post(route('tasks.start', task.id));
};

const completeTask = () => {
    router.post(route('tasks.complete', task.id));
};

const pauseTask = () => {
    router.post(route('tasks.pause', task.id));
};

const resumeTask = () => {
    router.post(route('tasks.resume', task.id));
};
</script>

<template>
    <Head :title="task.name" />

    <AppLayout>
        <div class="content">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><Link :href="route('tasks.index')">Task</Link></li>
                    <li class="is-active"><a href="#" aria-current="page">{{ task.name }}</a></li>
                </ul>
            </nav>

            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <h1 class="title is-2">{{ task.name }}</h1>
                            <p class="subtitle">
                                Progetto:
                                <Link :href="route('projects.show', task.project.id)">
                                    {{ task.project.name }}
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <div class="buttons">
                            <Link :href="route('tasks.edit', task.id)" class="button is-warning">
                                <span class="icon"><i class="fas fa-edit"></i></span>
                                <span>Modifica</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column is-4">
                    <div class="box">
                        <h2 class="title is-4">Dettagli Task</h2>
                        <div class="field">
                            <label class="label">Stato</label>
                            <p>
                                <span class="tag is-large" :class="getStatusClass(task.status)">
                                    {{ task.status.replace('_', ' ').toUpperCase() }}
                                </span>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label">Progetto</label>
                            <p>
                                <Link :href="route('projects.show', task.project.id)">
                                    {{ task.project.name }}
                                </Link>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label">Cliente</label>
                            <p>
                                <Link :href="route('contacts.show', task.project.contact.id)">
                                    {{ task.project.contact.name }}
                                </Link>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label">Scadenza</label>
                            <p>{{ formatDate(task.deadline) }}</p>
                        </div>
                        <div class="field">
                            <label class="label">Tempo di Lavoro</label>
                            <p>{{ formatWorkTime(task.total_work_time) }}</p>
                        </div>
                        <div class="field" v-if="task.started_at">
                            <label class="label">Iniziata il</label>
                            <p>{{ formatDate(task.started_at) }}</p>
                        </div>
                        <div class="field" v-if="task.completed_at">
                            <label class="label">Completata il</label>
                            <p>{{ formatDate(task.completed_at) }}</p>
                        </div>
                        <div class="field" v-if="task.description">
                            <label class="label">Descrizione</label>
                            <p>{{ task.description }}</p>
                        </div>
                    </div>

                    <div class="box">
                        <h2 class="title is-5">Azioni</h2>
                        <div class="buttons">
                            <button
                                @click="startTask"
                                class="button is-primary is-fullwidth"
                                v-if="task.status === 'da_fare'"
                            >
                                <span class="icon"><i class="fas fa-play"></i></span>
                                <span>Avvia Task</span>
                            </button>

                            <button
                                @click="completeTask"
                                class="button is-success is-fullwidth"
                                v-if="task.status === 'in_corso'"
                            >
                                <span class="icon"><i class="fas fa-check"></i></span>
                                <span>Completa Task</span>
                            </button>

                            <button
                                @click="pauseTask"
                                class="button is-warning is-fullwidth"
                                v-if="task.status === 'in_corso'"
                            >
                                <span class="icon"><i class="fas fa-pause"></i></span>
                                <span>Metti in Pausa</span>
                            </button>

                            <button
                                @click="resumeTask"
                                class="button is-info is-fullwidth"
                                v-if="task.status === 'in_pausa' || task.status === 'in_attesa'"
                            >
                                <span class="icon"><i class="fas fa-play"></i></span>
                                <span>Riprendi Task</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="column is-8">
                    <div class="box" v-if="task.blocked_by">
                        <article class="message is-warning">
                            <div class="message-header">
                                <p>Task Bloccata</p>
                            </div>
                            <div class="message-body">
                                Questa task è in attesa di:
                                <Link :href="route('tasks.show', task.blocked_by.id)" class="has-text-weight-bold">
                                    {{ task.blocked_by.name }}
                                </Link>
                            </div>
                        </article>
                    </div>

                    <div class="box" v-if="task.blocking_tasks && task.blocking_tasks.length > 0">
                        <h2 class="title is-4">Task Bloccate ({{ task.blocking_tasks.length }})</h2>
                        <p class="mb-3">Queste task sono in attesa del completamento di questa:</p>
                        <div v-for="blockedTask in task.blocking_tasks" :key="blockedTask.id" class="mb-2">
                            <Link :href="route('tasks.show', blockedTask.id)">
                                {{ blockedTask.name }}
                            </Link>
                        </div>
                    </div>

                    <div class="box">
                        <h2 class="title is-4">Timeline</h2>
                        <div class="timeline">
                            <div class="timeline-item" v-if="task.created_at">
                                <div class="timeline-marker is-info"></div>
                                <div class="timeline-content">
                                    <p class="heading">{{ formatDate(task.created_at) }}</p>
                                    <p>Task creata</p>
                                </div>
                            </div>
                            <div class="timeline-item" v-if="task.started_at">
                                <div class="timeline-marker is-warning"></div>
                                <div class="timeline-content">
                                    <p class="heading">{{ formatDate(task.started_at) }}</p>
                                    <p>Task avviata</p>
                                </div>
                            </div>
                            <div class="timeline-item" v-if="task.paused_at">
                                <div class="timeline-marker is-light"></div>
                                <div class="timeline-content">
                                    <p class="heading">{{ formatDate(task.paused_at) }}</p>
                                    <p>Task messa in pausa</p>
                                </div>
                            </div>
                            <div class="timeline-item" v-if="task.completed_at">
                                <div class="timeline-marker is-success"></div>
                                <div class="timeline-content">
                                    <p class="heading">{{ formatDate(task.completed_at) }}</p>
                                    <p>Task completata</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:not(:last-child):before {
    content: "";
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    height: 100%;
    width: 2px;
    background-color: #dbdbdb;
}

.timeline-marker {
    position: absolute;
    left: -1.75rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: #dbdbdb;
}

.timeline-marker.is-info {
    background-color: #3e8ed0;
}

.timeline-marker.is-warning {
    background-color: #ffe08a;
}

.timeline-marker.is-success {
    background-color: #48c78e;
}

.timeline-marker.is-light {
    background-color: #f5f5f5;
    border: 2px solid #dbdbdb;
}
</style>

