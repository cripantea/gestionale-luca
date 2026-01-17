<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    contacts: Array,
    projectTypes: Array,
});

const form = useForm({
    contact_id: '',
    project_type_id: '',
    name: '',
    description: '',
    status: 'attivo',
});

const submit = () => {
    form.post(route('projects.store'));
};
</script>

<template>
    <Head title="Nuovo Progetto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Nuovo Progetto
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <!-- Breadcrumb -->
                <nav class="mb-6 flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('projects.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                                Progetti
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
                    <p class="text-sm text-gray-600 dark:text-gray-400">Le task verranno generate automaticamente dal template</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                            <div class="p-6">
                                <form @submit.prevent="submit" class="space-y-6">
                                    
                                    <!-- Nome Progetto -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nome Progetto *
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

                                    <!-- Cliente -->
                                    <div>
                                        <label for="contact_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Cliente *
                                        </label>
                                        <select
                                            id="contact_id"
                                            v-model="form.contact_id"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.contact_id }"
                                        >
                                            <option value="">Seleziona un cliente</option>
                                            <option v-for="contact in contacts" :key="contact.id" :value="contact.id">
                                                {{ contact.name }} {{ contact.company ? `(${contact.company})` : '' }}
                                            </option>
                                        </select>
                                        <p v-if="form.errors.contact_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.contact_id }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Solo i clienti sono disponibili</p>
                                    </div>

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
                                            <option value="">Seleziona un tipo</option>
                                            <option v-for="type in projectTypes" :key="type.id" :value="type.id">
                                                {{ type.name }}
                                            </option>
                                        </select>
                                        <p v-if="form.errors.project_type_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.project_type_id }}
                                        </p>
                                    </div>

                                    <!-- Stato -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Stato
                                        </label>
                                        <select
                                            id="status"
                                            v-model="form.status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                        >
                                            <option value="attivo">Attivo</option>
                                            <option value="completato">Completato</option>
                                            <option value="sospeso">Sospeso</option>
                                        </select>
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
                                                    <strong>Nota:</strong> Quando salvi il progetto, verranno create automaticamente tutte le task del template associato al tipo di progetto selezionato.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <Link
                                            :href="route('projects.index')"
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
                                            Crea Progetto
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div>
                        <div class="overflow-hidden bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 shadow-sm sm:rounded-lg dark:from-indigo-900/20 dark:to-blue-900/20 dark:border-indigo-800">
                            <div class="p-6">
                                <h3 class="text-sm font-semibold text-indigo-900 dark:text-indigo-100 mb-3">
                                    ℹ️ Come Funziona
                                </h3>
                                <div class="space-y-3 text-sm text-indigo-800 dark:text-indigo-200">
                                    <p>
                                        <strong>1.</strong> Seleziona il cliente per cui stai creando il progetto
                                    </p>
                                    <p>
                                        <strong>2.</strong> Scegli il tipo di progetto (es: Sito Web, Social Media)
                                    </p>
                                    <p>
                                        <strong>3.</strong> Le task del template verranno create automaticamente
                                    </p>
                                    <p class="pt-3 border-t border-indigo-200 dark:border-indigo-700">
                                        💡 <strong>Suggerimento:</strong> Puoi modificare le task dopo la creazione del progetto
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

