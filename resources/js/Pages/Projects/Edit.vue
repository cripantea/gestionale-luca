<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    project: Object,
    contacts: Array,
    projectTypes: Array,
});

const form = useForm({
    contact_id: props.project.contact_id,
    project_type_id: props.project.project_type_id,
    name: props.project.name,
    description: props.project.description,
    status: props.project.status,
});

const submit = () => {
    form.put(route('projects.update', props.project.id));
};
</script>

<template>
    <Head :title="`Modifica ${project.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Modifica Progetto: {{ project.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
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
                                <Link :href="route('projects.show', project.id)" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400">
                                    {{ project.name }}
                                </Link>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Modifica</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Nome Progetto -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Progetto *</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    id="name"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>

                            <!-- Cliente -->
                            <div>
                                <label for="contact_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente *</label>
                                <select
                                    v-model="form.contact_id"
                                    id="contact_id"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    :class="{ 'border-red-500': form.errors.contact_id }"
                                >
                                    <option value="">Seleziona un cliente</option>
                                    <option v-for="contact in contacts" :key="contact.id" :value="contact.id">
                                        {{ contact.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.contact_id" class="mt-2 text-sm text-red-600">{{ form.errors.contact_id }}</p>
                            </div>

                            <!-- Tipo Progetto -->
                            <div>
                                <label for="project_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo Progetto *</label>
                                <select
                                    v-model="form.project_type_id"
                                    id="project_type_id"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    :class="{ 'border-red-500': form.errors.project_type_id }"
                                >
                                    <option value="">Seleziona un tipo</option>
                                    <option v-for="type in projectTypes" :key="type.id" :value="type.id">
                                        {{ type.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.project_type_id" class="mt-2 text-sm text-red-600">{{ form.errors.project_type_id }}</p>
                            </div>

                            <!-- Descrizione -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrizione</label>
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    :class="{ 'border-red-500': form.errors.description }"
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stato *</label>
                                <select
                                    v-model="form.status"
                                    id="status"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    :class="{ 'border-red-500': form.errors.status }"
                                >
                                    <option value="attivo">Attivo</option>
                                    <option value="completato">Completato</option>
                                    <option value="sospeso">Sospeso</option>
                                    <option value="annullato">Annullato</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <Link :href="route('projects.show', project.id)" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    Annulla
                                </Link>
                                <button type="submit" :disabled="form.processing"
                                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Salva Modifiche
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
