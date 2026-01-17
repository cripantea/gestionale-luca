<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    clients: Array,
    projects: Array,
});

const selectedClientId = ref('');

const form = useForm({
    project_id: '',
    name: '',
    description: '',
    deadline: '',
    blocked_by_task_id: null,
    priority: 'media',
    estimated_hours: null,
    is_recurring: false,
    recurrence_pattern: 'weekly',
    recurrence_interval: 1,
    recurrence_end_date: null,
});

// Filtra progetti in base al cliente selezionato
const filteredProjects = computed(() => {
    if (!selectedClientId.value) return [];
    return props.projects.filter(project => project.contact_id == selectedClientId.value);
});

// Trova il cliente selezionato
const selectedClient = computed(() => {
    if (!selectedClientId.value) return null;
    return props.clients.find(c => c.id == selectedClientId.value);
});

// Reset project quando cambia cliente
const onClientChange = () => {
    form.project_id = '';
};

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
                                    
                                    <!-- Step 1: Cliente -->
                                    <div>
                                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold mr-2">1</span>
                                            Seleziona Cliente *
                                        </label>
                                        <select
                                            id="client_id"
                                            v-model="selectedClientId"
                                            @change="onClientChange"
                                            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            required
                                        >
                                            <option value="">Seleziona un cliente</option>
                                            <option v-for="client in clients" :key="client.id" :value="client.id">
                                                {{ client.name }} {{ client.company ? `(${client.company})` : '' }} - {{ client.projects_count }} progetti
                                            </option>
                                        </select>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Scegli il cliente per cui vuoi creare la task
                                        </p>
                                    </div>

                                    <!-- Step 2: Progetto -->
                                    <div v-if="selectedClientId" class="animate-fade-in">
                                        <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold mr-2">2</span>
                                            Seleziona Progetto *
                                        </label>
                                        <select
                                            id="project_id"
                                            v-model="form.project_id"
                                            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.project_id }"
                                            required
                                        >
                                            <option value="">Seleziona un progetto</option>
                                            <option v-for="project in filteredProjects" :key="project.id" :value="project.id">
                                                {{ project.name }} ({{ project.project_type?.name }})
                                            </option>
                                        </select>
                                        <p v-if="filteredProjects.length === 0" class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                            ⚠️ Questo cliente non ha ancora progetti. <Link :href="route('projects.create')" class="underline hover:text-amber-800">Creane uno</Link>
                                        </p>
                                        <p v-else class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ filteredProjects.length }} progetto/i disponibile/i per {{ selectedClient?.name }}
                                        </p>
                                        <p v-if="form.errors.project_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.project_id }}
                                        </p>
                                    </div>

                                    <!-- Divider -->
                                    <div v-if="form.project_id" class="border-t border-gray-200 dark:border-gray-700 pt-6 animate-fade-in">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold mr-2">3</span>
                                            Dettagli Task
                                        </h3>
                                    </div>

                                    <!-- Nome Task -->
                                    <div v-if="form.project_id" class="animate-fade-in">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nome Task *
                                        </label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.name }"
                                            placeholder="es. Configurazione SEO, Design homepage, etc."
                                            required
                                        />
                                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.name }}
                                        </p>
                                    </div>

                                    <!-- Scadenza -->
                                    <div v-if="form.project_id" class="animate-fade-in">
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
                                    <div v-if="form.project_id" class="animate-fade-in">
                                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Descrizione
                                        </label>
                                        <textarea
                                            id="description"
                                            v-model="form.description"
                                            rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                            :class="{ 'border-red-500': form.errors.description }"
                                            placeholder="Descrivi cosa deve essere fatto..."
                                        ></textarea>
                                        <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.description }}
                                        </p>
                                    </div>

                                    <!-- Impostazioni Avanzate -->
                                    <div v-if="form.project_id" class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6 space-y-6 animate-fade-in">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            ⚙️ Impostazioni Avanzate
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Priorità -->
                                            <div>
                                                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Priorità
                                                </label>
                                                <select
                                                    id="priority"
                                                    v-model="form.priority"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                                >
                                                    <option value="bassa">🟢 Bassa</option>
                                                    <option value="media">🟡 Media</option>
                                                    <option value="alta">🟠 Alta</option>
                                                    <option value="critica">🔴 Critica</option>
                                                </select>
                                            </div>

                                            <!-- Ore Stimate -->
                                            <div>
                                                <label for="estimated_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Ore Stimate
                                                </label>
                                                <input
                                                    id="estimated_hours"
                                                    v-model="form.estimated_hours"
                                                    type="number"
                                                    step="0.5"
                                                    min="0"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                                    placeholder="Es: 2.5"
                                                />
                                            </div>
                                        </div>

                                        <!-- Task Ricorrente -->
                                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <input
                                                    id="is_recurring"
                                                    v-model="form.is_recurring"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                                />
                                                <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    🔄 Rendi questa task ricorrente
                                                </label>
                                            </div>

                                            <!-- Campi ricorrenza (solo se abilitato) -->
                                            <div v-if="form.is_recurring" class="mt-4 space-y-4 animate-fade-in">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <!-- Pattern ricorrenza -->
                                                    <div>
                                                        <label for="recurrence_pattern" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Frequenza
                                                        </label>
                                                        <select
                                                            id="recurrence_pattern"
                                                            v-model="form.recurrence_pattern"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                                        >
                                                            <option value="daily">📅 Giornaliera</option>
                                                            <option value="weekly">📆 Settimanale</option>
                                                            <option value="monthly">🗓️ Mensile</option>
                                                            <option value="yearly">📊 Annuale</option>
                                                        </select>
                                                    </div>

                                                    <!-- Intervallo -->
                                                    <div>
                                                        <label for="recurrence_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Ogni
                                                        </label>
                                                        <input
                                                            id="recurrence_interval"
                                                            v-model="form.recurrence_interval"
                                                            type="number"
                                                            min="1"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                                        />
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                            Es: 1 = ogni settimana, 2 = ogni 2 settimane
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Data fine ricorrenza -->
                                                <div>
                                                    <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Data Fine Ricorrenza (opzionale)
                                                    </label>
                                                    <input
                                                        id="recurrence_end_date"
                                                        v-model="form.recurrence_end_date"
                                                        type="date"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                                    />
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        Lascia vuoto per ricorrenza infinita
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div v-if="form.project_id" class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 animate-fade-in">
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
                                            <span v-if="form.is_recurring">🔄 Crea Task Ricorrente</span>
                                            <span v-else>Crea Task</span>
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
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Workflow Creazione Task</h3>
                                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                            <ol class="list-decimal list-inside space-y-2">
                                                <li><strong>Seleziona il cliente</strong> - Scegli per chi stai lavorando</li>
                                                <li><strong>Scegli il progetto</strong> - Seleziona il progetto specifico del cliente</li>
                                                <li><strong>Compila i dettagli</strong> - Nome, scadenza e descrizione della task</li>
                                            </ol>
                                            <div class="mt-4 pt-4 border-t border-blue-300 dark:border-blue-700">
                                                <p class="font-medium">📝 Note:</p>
                                                <ul class="list-disc list-inside space-y-1 mt-1">
                                                    <li>La task inizierà con stato "Da Fare"</li>
                                                    <li>Potrai gestire gli stati dalla pagina di dettaglio</li>
                                                    <li>Il tempo di lavoro viene tracciato automaticamente</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Client Info -->
                        <div v-if="selectedClient" class="mt-6 overflow-hidden bg-green-50 border border-green-200 shadow-sm sm:rounded-lg dark:bg-green-900/20 dark:border-green-800 animate-fade-in">
                            <div class="p-6">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200 mb-2">✓ Cliente Selezionato</h3>
                                <div class="text-sm text-green-700 dark:text-green-300">
                                    <p class="font-semibold">{{ selectedClient.name }}</p>
                                    <p v-if="selectedClient.company" class="text-xs">{{ selectedClient.company }}</p>
                                    <p class="text-xs mt-1">{{ selectedClient.projects_count }} progetti attivi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>
