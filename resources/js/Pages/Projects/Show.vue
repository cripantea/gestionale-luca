<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    project: Object,
});
</script>

<template>
    <Head :title="project.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ project.name }}
                </h2>
                <Link :href="route('projects.edit', project.id)" class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700">
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
                            <Link :href="route('projects.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400">
                                Progetti
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ project.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Dettagli Progetto -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Dettagli Progetto
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo Progetto</dt>
                                    <dd class="mt-1">
                                        <Link :href="route('project-types.show', project.project_type.id)">
                                            <span class="inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800">
                                                {{ project.project_type.name }}
                                            </span>
                                        </Link>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</dt>
                                    <dd class="mt-1">
                                        <Link :href="route('contacts.show', project.contact.id)" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ project.contact.name }}
                                        </Link>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stato</dt>
                                    <dd class="mt-1">
                                        <span :class="project.status === 'attivo' ? 'bg-green-100 text-green-800' : project.status === 'completato' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ project.status.charAt(0).toUpperCase() + project.status.slice(1) }}
                                        </span>
                                    </dd>
                                </div>
                                <div v-if="project.description">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrizione</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ project.description }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data Creazione</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">
                                        {{ new Date(project.created_at).toLocaleDateString('it-IT') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Task del Progetto -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 lg:col-span-2">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Task ({{ project.tasks?.length || 0 }})
                            </h3>
                        </div>
                        <div v-if="project.tasks && project.tasks.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Task</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Scadenza</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Stato</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                    <tr v-for="task in project.tasks" :key="task.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <Link :href="route('tasks.show', task.id)" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                {{ task.name }}
                                            </Link>
                                            <p v-if="task.description" class="mt-1 text-xs text-gray-500">{{ task.description }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ new Date(task.deadline).toLocaleDateString('it-IT') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span :class="task.status === 'completato' ? 'bg-green-100 text-green-800' : task.status === 'in_corso' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                                {{ task.status.replace('_', ' ').toUpperCase() }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                            <Link :href="route('tasks.show', task.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                Vedi
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="p-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nessuna task associata a questo progetto</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

