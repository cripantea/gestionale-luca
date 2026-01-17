<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    taskTemplate: Object,
    projectTypes: Array,
});

const form = useForm({
    project_type_id: props.taskTemplate.project_type_id,
    name: props.taskTemplate.name,
    description: props.taskTemplate.description,
    default_deadline_days: props.taskTemplate.default_deadline_days,
    order: props.taskTemplate.order,
});

const submit = () => {
    form.put(route('task-templates.update', props.taskTemplate.id));
};
</script>

<template>
    <Head :title="`Modifica ${taskTemplate.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Modifica Template Task
            </h2>
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
                                <Link :href="route('task-templates.show', taskTemplate.id)" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                                    {{ taskTemplate.name }}
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

                <div class="max-w-3xl">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <form @submit.prevent="submit" class="space-y-6">
                                
                                <!-- Tipo Progetto -->
                                <div>
                                    <label for="project_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tipo Progetto *
                                    </label>
                                    <select
                                        id="project_type_id"
                                        v-model="form.project_type_id"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.project_type_id }"
                                    >
                                        <option value="">Seleziona un tipo di progetto</option>
                                        <option v-for="type in projectTypes" :key="type.id" :value="type.id">
                                            {{ type.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.project_type_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.project_type_id }}
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
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Descrizione -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Descrizione
                                    </label>
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                        :class="{ 'border-red-500': form.errors.description }"
                                    ></textarea>
                                    <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.description }}
                                    </p>
                                </div>

                                <!-- Scadenza e Ordine -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="default_deadline_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Scadenza Default (giorni) *
                                        </label>
                                        <input
                                            id="default_deadline_days"
                                            v-model.number="form.default_deadline_days"
                                            type="number"
                                            min="1"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.default_deadline_days }"
                                        />
                                        <p v-if="form.errors.default_deadline_days" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.default_deadline_days }}
                                        </p>
                                    </div>

                                    <div>
                                        <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Ordine di Esecuzione *
                                        </label>
                                        <input
                                            id="order"
                                            v-model.number="form.order"
                                            type="number"
                                            min="0"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.order }"
                                        />
                                        <p v-if="form.errors.order" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.order }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <Link
                                        :href="route('task-templates.show', taskTemplate.id)"
                                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        Annulla
                                    </Link>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Aggiorna
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
