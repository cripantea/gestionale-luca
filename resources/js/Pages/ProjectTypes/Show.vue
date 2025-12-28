<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    projectType: Object,
});
</script>

<template>
    <Head :title="projectType.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ projectType.name }}
                </h2>
                <Link :href="route('project-types.edit', projectType.id)" class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700">
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
                            <Link :href="route('project-types.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400">
                                Tipi Progetto
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ projectType.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Template Task -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Template Task ({{ projectType.task_templates?.length || 0 }})
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Task generate automaticamente per questo tipo di progetto
                            </p>
                        </div>
                        <div class="p-6">
                            <div v-if="projectType.task_templates && projectType.task_templates.length > 0" class="space-y-3">
                                <div v-for="template in projectType.task_templates" :key="template.id" class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex rounded-full bg-gray-800 px-2 text-xs font-semibold leading-5 text-white">
                                                    #{{ template.order }}
                                                </span>
                                                <Link :href="route('task-templates.show', template.id)" class="font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                    {{ template.name }}
                                                </Link>
                                            </div>
                                            <p v-if="template.description" class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ template.description }}</p>
                                        </div>
                                        <span class="ml-3 inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800">
                                            {{ template.default_deadline_days }}gg
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nessun template configurato</p>
                                <Link :href="route('task-templates.create')" class="mt-4 inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                    Aggiungi Template Task
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Progetti -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Progetti ({{ projectType.projects?.length || 0 }})
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Progetti che usano questo tipo
                            </p>
                        </div>
                        <div class="p-6">
                            <div v-if="projectType.projects && projectType.projects.length > 0" class="space-y-4">
                                <div v-for="project in projectType.projects" :key="project.id" class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                    <Link :href="route('projects.show', project.id)" class="text-base font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ project.name }}
                                    </Link>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Cliente: {{ project.contact.name }}
                                    </p>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500 dark:text-gray-400">Nessun progetto di questo tipo</p>
                        </div>
                    </div>

                    <!-- Info Tipo Progetto -->
                    <div v-if="projectType.description" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 lg:col-span-2">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Descrizione
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-900 dark:text-gray-300">{{ projectType.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

