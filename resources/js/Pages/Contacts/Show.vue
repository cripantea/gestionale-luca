<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
});
</script>

<template>
    <Head :title="contact.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ contact.name }}
                </h2>
                <Link :href="route('contacts.edit', contact.id)" class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700">
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
                            <Link :href="route('contacts.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400">
                                Contatti
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ contact.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Informazioni Contatto -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Informazioni
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stato</dt>
                                    <dd class="mt-1">
                                        <span :class="contact.status === 'cliente' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ contact.status === 'cliente' ? 'Cliente' : 'Contatto' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ contact.email || '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefono</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ contact.phone || '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Azienda</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ contact.company || '-' }}</dd>
                                </div>
                                <div v-if="contact.notes">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Note</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300">{{ contact.notes }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Progetti -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Progetti ({{ contact.projects?.length || 0 }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div v-if="contact.projects && contact.projects.length > 0" class="space-y-4">
                                <div v-for="project in contact.projects" :key="project.id" class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                    <Link :href="route('projects.show', project.id)" class="text-base font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ project.name }}
                                    </Link>
                                    <div class="mt-2 flex items-center space-x-2">
                                        <span class="inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800">
                                            {{ project.project_type.name }}
                                        </span>
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800">
                                            {{ project.tasks.length }} task
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500 dark:text-gray-400">Nessun progetto associato</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

