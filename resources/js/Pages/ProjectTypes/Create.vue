<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('project-types.store'));
};
</script>

<template>
    <Head title="Nuovo Tipo Progetto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Nuovo Tipo Progetto
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <!-- Breadcrumb -->
                <nav class="mb-6 flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('project-types.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
                                Tipi Progetto
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
                    <p class="text-sm text-gray-600 dark:text-gray-400">Dopo aver creato il tipo, potrai aggiungere i template task</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                            <div class="p-6">
                                <form @submit.prevent="submit" class="space-y-6">
                                    
                                    <!-- Nome -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nome *
                                        </label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            required
                                            placeholder="es: Sviluppo Sito Web, SEO, Marketing..."
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
                                            rows="4"
                                            placeholder="Descrivi questo tipo di progetto..."
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
                                                    <strong>Prossimo passo:</strong> Dopo aver creato questo tipo di progetto, dovrai configurare i template task che verranno generati automaticamente quando si crea un progetto di questo tipo.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <Link
                                            :href="route('project-types.index')"
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
                                            Crea Tipo Progetto
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div>
                        <div class="overflow-hidden bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-100 shadow-sm sm:rounded-lg dark:from-purple-900/20 dark:to-indigo-900/20 dark:border-purple-800">
                            <div class="p-6">
                                <h3 class="text-sm font-semibold text-purple-900 dark:text-purple-100 mb-3">
                                    💡 Esempi di Tipi
                                </h3>
                                <div class="space-y-2 text-sm text-purple-800 dark:text-purple-200">
                                    <div class="p-2 bg-white/50 rounded dark:bg-white/10">
                                        <strong>Sito Web</strong>
                                        <p class="text-xs">Design, sviluppo, deploy</p>
                                    </div>
                                    <div class="p-2 bg-white/50 rounded dark:bg-white/10">
                                        <strong>Social Media</strong>
                                        <p class="text-xs">Calendario, contenuti, ads</p>
                                    </div>
                                    <div class="p-2 bg-white/50 rounded dark:bg-white/10">
                                        <strong>Fusion Core</strong>
                                        <p class="text-xs">Setup, automazioni, test</p>
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
