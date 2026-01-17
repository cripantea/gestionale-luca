<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array,
});

const form = useForm({
    project_id: '',
    name: '',
    description: '',
    deadline: '',
    blocked_by_task_id: null,
});

const submit = () => {
    form.post(route('tasks.store'));
};

// Calcola data di default (3 giorni da oggi)
const defaultDeadline = () => {
    const date = new Date();
    date.setDate(date.getDate() + 3);
    return date.toISOString().slice(0, 16);
};

if (!form.deadline) {
    form.deadline = defaultDeadline();
}
</script>

<template>
    <Head title="Nuova Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Nuova Task
            </h2>
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
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Nuova</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Form Column -->
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                            <div class="p-6">
                                <form @submit.prevent="submit" class="space-y-6">
                                    
                                    <!-- Progetto -->
                                    <div>
                                        <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Progetto *
                                        </label>
                                        <select
                                            id="project_id"
                                            v-model="form.project_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.project_id }"
                                            required
                                        >
                                            <option value="">Seleziona un progetto</option>
                                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                                {{ project.name }} - {{ project.contact.name }}
                                            </option>
                                        </select>
                                        <p v-if="form.errors.project_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.project_id }}
                                        </p>
                                    </div>

                                    <!-- Nome Task -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nome Task *
                                        </label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.name }"
                                            required
                                        />
                                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.name }}
                                        </p>
                                    </div>

                                    <!-- Scadenza -->
                                    <div>
                                        <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Scadenza *
                                        </label>
                                        <input
                                            id="deadline"
                                            v-model="form.deadline"
                                            type="datetime-local"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.deadline }"
                                            required
                                        />
                                        <p v-if="form.errors.deadline" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.deadline }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Default: 3 giorni da oggi</p>
                                    </div>

                                    <!-- Descrizione -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Descrizione
                                        </label>
                                        <textarea
                                            id="description"
                                            v-model="form.description"
                                            rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.description }"
                                        ></textarea>
                                        <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.description }}
                                        </p>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <Link
                                            :href="route('tasks.index')"
                                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            Annulla
                                        </Link>
                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                            :disabled="form.processing"
                                        >
                                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Crea Task
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info Column -->
                    <div>
                        <div class="overflow-hidden bg-blue-50 border border-blue-200 shadow-sm sm:rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                            <div class="p-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Informazioni</h3>
                                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>La task verrà creata con stato "Da Fare"</li>
                                                <li>Potrai gestire gli stati dalla pagina di dettaglio</li>
                                                <li>Le dipendenze possono essere configurate successivamente</li>
                                                <li>Il tempo di lavoro viene tracciato automaticamente</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

