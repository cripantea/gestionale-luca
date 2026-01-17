<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    projectTypes: Array,
});

const form = useForm({
    project_type_id: '',
    name: '',
    description: '',
    default_deadline_days: 3,
    order: 1,
});

const submit = () => {
    form.post(route('task-templates.store'));
};
</script>

<template>
    <Head title="Nuovo Template Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Nuovo Template Task
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
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Nuovo</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Questo template verrà usato per generare task automaticamente</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
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
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Per quale tipo di progetto è questo template?</p>
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
                                            placeholder="es: Analisi Requisiti, Design, Sviluppo..."
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
                                            placeholder="Descrivi cosa comporta questa task..."
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.description }"
                                        ></textarea>
                                        <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.description }}
                                        </p>
                                    </div>

                                    <!-- Scadenza Default -->
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
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Giorni dalla creazione progetto</p>
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
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">0 = prima, 1 = seconda, ecc.</p>
                                        </div>
                                    </div>

                                    <!-- Info Box -->
                                    <div class="rounded-md bg-blue-50 border border-blue-200 p-4 dark:bg-blue-900/20 dark:border-blue-800">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                                    <strong>Come funziona:</strong> Quando viene creato un progetto del tipo selezionato, verrà generata automaticamente una task con questi parametri. La scadenza sarà calcolata come "data creazione progetto + giorni specificati".
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <Link
                                            :href="route('task-templates.index')"
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
                                            Crea Template
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div>
                        <div class="overflow-hidden bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 shadow-sm sm:rounded-lg dark:from-green-900/20 dark:to-emerald-900/20 dark:border-green-800">
                            <div class="p-6">
                                <h3 class="text-sm font-semibold text-green-900 dark:text-green-100 mb-3">
                                    🎯 Suggerimenti
                                </h3>
                                <div class="space-y-3 text-sm text-green-800 dark:text-green-200">
                                    <p>
                                        <strong>Ordine:</strong> Numera le task in ordine logico (0, 1, 2...)
                                    </p>
                                    <p>
                                        <strong>Scadenze:</strong> Sii realistico con i tempi. Meglio sovrastimare che sottostimare.
                                    </p>
                                    <p>
                                        <strong>Nomi:</strong> Usa nomi chiari e actionable (es: "Crea Design", non "Design")
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
