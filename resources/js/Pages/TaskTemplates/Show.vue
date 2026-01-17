<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    taskTemplate: Object,
});
</script>

<template>
    <Head :title="taskTemplate.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ taskTemplate.name }}
                </h2>
                <Link
                    :href="route('task-templates.edit', taskTemplate.id)"
                    class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700 transition-colors"
                >
                    <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
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
                            <Link :href="route('task-templates.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                                Template Task
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ taskTemplate.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Badge Tipo Progetto -->
                <div class="mb-6">
                    <Link :href="route('project-types.show', taskTemplate.project_type.id)">
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-800 hover:bg-indigo-200 transition-colors dark:bg-indigo-900/50 dark:text-indigo-200">
                            {{ taskTemplate.project_type.name }}
                        </span>
                    </Link>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Dettagli Template -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Dettagli Template
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Task</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ taskTemplate.name }}</dd>
                                </div>
                                <div v-if="taskTemplate.description">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrizione</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ taskTemplate.description }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo Progetto</dt>
                                    <dd class="mt-1">
                                        <Link :href="route('project-types.show', taskTemplate.project_type.id)">
                                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 hover:bg-indigo-100 dark:bg-indigo-900/50 dark:text-indigo-200">
                                                {{ taskTemplate.project_type.name }}
                                            </span>
                                        </Link>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Scadenza Default</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/10 dark:bg-gray-700 dark:text-gray-300">
                                            {{ taskTemplate.default_deadline_days }} giorn{{ taskTemplate.default_deadline_days === 1 ? 'o' : 'i' }} dalla creazione
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ordine di Esecuzione</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center rounded-md bg-gray-900 px-2 py-1 text-xs font-medium text-white dark:bg-gray-600">
                                            #{{ taskTemplate.order }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Utilizzo -->
                    <div class="space-y-6">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    Utilizzo
                                </h3>
                                <div class="rounded-md bg-blue-50 border border-blue-200 p-4 dark:bg-blue-900/20 dark:border-blue-800">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                                                Questo template viene usato per generare automaticamente una task quando viene creato un progetto di tipo
                                                <strong>{{ taskTemplate.project_type.name }}</strong>.
                                            </p>
                                            <p class="text-sm text-blue-700 dark:text-blue-300 font-semibold mb-2">
                                                La task generata avrà:
                                            </p>
                                            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                                <li>• Nome: <strong>{{ taskTemplate.name }}</strong></li>
                                                <li>• Descrizione: {{ taskTemplate.description || '(nessuna)' }}</li>
                                                <li>• Scadenza: {{ taskTemplate.default_deadline_days }} giorni dalla creazione del progetto</li>
                                                <li>• Ordine: {{ taskTemplate.order }}° nella lista delle task</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Link a altri template -->
                        <div class="overflow-hidden bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 shadow-sm sm:rounded-lg dark:from-indigo-900/20 dark:to-blue-900/20 dark:border-indigo-800">
                            <div class="p-6">
                                <h3 class="text-sm font-semibold text-indigo-900 dark:text-indigo-100 mb-2">
                                    Altri Template di {{ taskTemplate.project_type.name }}
                                </h3>
                                <p class="text-xs text-indigo-700 dark:text-indigo-300 mb-3">
                                    Tutti i template task per questo tipo di progetto
                                </p>
                                <Link
                                    :href="route('project-types.show', taskTemplate.project_type.id)"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors"
                                >
                                    <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    Vedi tutti i template
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
