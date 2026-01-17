<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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

const getStatusBadge = (status) => {
    const badges = {
        'da_fare': 'bg-blue-100 text-blue-800 border-blue-200',
        'in_corso': 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'completato': 'bg-green-100 text-green-800 border-green-200',
        'in_pausa': 'bg-gray-100 text-gray-800 border-gray-200',
        'in_attesa': 'bg-purple-100 text-purple-800 border-purple-200',
    };
    return badges[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusLabel = (status) => {
    const labels = {
        'da_fare': 'Da Fare',
        'in_corso': 'In Corso',
        'completato': 'Completato',
        'in_pausa': 'In Pausa',
        'in_attesa': 'In Attesa',
    };
    return labels[status] || status;
};

const startTask = () => {
    router.post(route('tasks.start', props.task.id));
};

const completeTask = () => {
    router.post(route('tasks.complete', props.task.id));
};

const pauseTask = () => {
    router.post(route('tasks.pause', props.task.id));
};

const resumeTask = () => {
    router.post(route('tasks.resume', props.task.id));
};
</script>

<template>
    <Head :title="task.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        {{ task.name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Progetto: 
                        <Link :href="route('projects.show', task.project.id)" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                            {{ task.project.name }}
                        </Link>
                    </p>
                </div>
                <Link :href="route('tasks.edit', task.id)" class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                    </svg>
                    Modifica
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- Breadcrumb -->
                <nav class="mb-6 flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('tasks.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                                Task
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ task.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    
                    <!-- Left Column: Details & Actions -->
                    <div class="space-y-6">
                        <!-- Details Card -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 transition-all hover:shadow-md">
                            <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Dettagli Task
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stato</dt>
                                    <dd class="mt-1">
                                        <span :class="getStatusBadge(task.status)" class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold">
                                            {{ getStatusLabel(task.status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Progetto</dt>
                                    <dd class="mt-1">
                                        <Link :href="route('projects.show', task.project.id)" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ task.project.name }}
                                        </Link>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</dt>
                                    <dd class="mt-1">
                                        <Link :href="route('clients.show', task.project.contact.id)" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ task.project.contact.name }}
                                        </Link>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Scadenza</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ formatDate(task.deadline) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempo di Lavoro</dt>
                                    <dd class="mt-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ formatWorkTime(task.total_work_time) }}</dd>
                                </div>
                                <div v-if="task.started_at">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Iniziata il</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ formatDate(task.started_at) }}</dd>
                                </div>
                                <div v-if="task.completed_at">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Completata il</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ formatDate(task.completed_at) }}</dd>
                                </div>
                                <div v-if="task.description">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrizione</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ task.description }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 transition-all hover:shadow-md">
                            <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Azioni Rapide
                                </h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <button
                                    @click="startTask"
                                    class="w-full inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all hover:scale-105"
                                    v-if="task.status === 'da_fare'"
                                >
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                    </svg>
                                    Avvia Task
                                </button>

                                <button
                                    @click="completeTask"
                                    class="w-full inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all hover:scale-105"
                                    v-if="task.status === 'in_corso'"
                                >
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Completa Task
                                </button>

                                <button
                                    @click="pauseTask"
                                    class="w-full inline-flex items-center justify-center rounded-md border border-transparent bg-yellow-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all hover:scale-105"
                                    v-if="task.status === 'in_corso'"
                                >
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Metti in Pausa
                                </button>

                                <button
                                    @click="resumeTask"
                                    class="w-full inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all hover:scale-105"
                                    v-if="task.status === 'in_pausa' || task.status === 'in_attesa'"
                                >
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                    </svg>
                                    Riprendi Task
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Timeline & Dependencies -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Blocked Warning -->
                        <div v-if="task.blocked_by" class="overflow-hidden bg-yellow-50 border-l-4 border-yellow-400 shadow-sm sm:rounded-lg dark:bg-yellow-900/20 dark:border-yellow-500">
                            <div class="p-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Task Bloccata</h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>
                                                Questa task è in attesa di: 
                                                <Link :href="route('tasks.show', task.blocked_by.id)" class="font-semibold underline hover:text-yellow-900 dark:hover:text-yellow-100">
                                                    {{ task.blocked_by.name }}
                                                </Link>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Blocking Tasks -->
                        <div v-if="task.blocking_tasks && task.blocking_tasks.length > 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 transition-all hover:shadow-md">
                            <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Task Bloccate ({{ task.blocking_tasks.length }})
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Queste task sono in attesa del completamento di questa</p>
                            </div>
                            <div class="p-6">
                                <ul class="space-y-3">
                                    <li v-for="blockedTask in task.blocking_tasks" :key="blockedTask.id">
                                        <Link :href="route('tasks.show', blockedTask.id)" class="block rounded-lg border border-gray-200 p-3 hover:border-indigo-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:border-indigo-600 dark:hover:bg-gray-700/50 transition-all">
                                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ blockedTask.name }}</span>
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 transition-all hover:shadow-md">
                            <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Timeline
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        <li v-if="task.created_at" class="relative pb-8">
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-gray-100">Task creata</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                        {{ formatDate(task.created_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li v-if="task.started_at" class="relative pb-8">
                                            <span v-if="task.paused_at || task.completed_at" class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-gray-100">Task avviata</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                        {{ formatDate(task.started_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li v-if="task.paused_at" class="relative pb-8">
                                            <span v-if="task.completed_at" class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-gray-100">Task messa in pausa</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                        {{ formatDate(task.paused_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li v-if="task.completed_at" class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-gray-100">Task completata</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                        {{ formatDate(task.completed_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

