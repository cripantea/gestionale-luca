<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskKanban from '@/Components/TaskKanban.vue';
import { Link, Head, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    tasks: Array,
    showCompleted: Boolean,
});

// Vista (lista o kanban)
const viewMode = ref('list'); // 'list' or 'kanban'

// Filtri e ricerca
const searchQuery = ref('');
const statusFilter = ref('all');
const projectFilter = ref('all');

// Funzioni di formattazione
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusBadge = (status) => {
    const badges = {
        'da_fare': 'bg-blue-100 text-blue-800 border-blue-200',
        'in_corso': 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'completato': 'bg-green-100 text-green-800 border-green-200',
        'in_pausa': 'bg-gray-100 text-gray-800 border-gray-200',
        'in_attesa': 'bg-purple-100 text-purple-800 border-purple-200',
    };
    return badges[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusLabel = (status) => {
    const labels = {
        'da_fare': 'Da Fare',
        'in_corso': 'In Corso',
        'completato': 'Completato',
        'in_pausa': 'In Pausa',
        'in_attesa': 'In Attesa',
    };
    return labels[status] || status;
};

// Azioni rapide
const startTask = (taskId) => {
    router.post(route('tasks.start', taskId));
};

const completeTask = (taskId) => {
    router.post(route('tasks.complete', taskId));
};

// Lista progetti unici
const uniqueProjects = computed(() => {
    const projects = props.tasks.map(task => ({
        id: task.project.id,
        name: task.project.name
    }));
    return [...new Map(projects.map(p => [p.id, p])).values()];
});

// Filtro delle task
const filteredTasks = computed(() => {
    return props.tasks.filter(task => {
        // Filtro ricerca
        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            const matchesName = task.name.toLowerCase().includes(query);
            const matchesProject = task.project.name.toLowerCase().includes(query);
            const matchesContact = task.project.contact.name.toLowerCase().includes(query);
            if (!matchesName && !matchesProject && !matchesContact) return false;
        }

        // Filtro stato
        if (statusFilter.value !== 'all' && task.status !== statusFilter.value) {
            return false;
        }

        // Filtro progetto
        if (projectFilter.value !== 'all' && task.project.id !== parseInt(projectFilter.value)) {
            return false;
        }

        return true;
    });
});

// Reset filtri
const resetFilters = () => {
    searchQuery.value = '';
    statusFilter.value = 'all';
    projectFilter.value = 'all';
};

// Toggle per mostrare task completate
const toggleCompleted = () => {
    router.get(route('tasks.index'), { show_completed: !props.showCompleted }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <Head title="Task" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Task
                </h2>
                <div class="flex items-center space-x-3">
                    <!-- View Toggle -->
                    <div class="inline-flex rounded-md shadow-sm">
                        <button
                            @click="viewMode = 'list'"
                            type="button"
                            :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button
                            @click="viewMode = 'kanban'"
                            type="button"
                            :class="viewMode === 'kanban' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium border-t border-b border-r border-gray-300 dark:border-gray-600 rounded-r-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4zM8 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H9a1 1 0 01-1-1V4zM15 3a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V4a1 1 0 00-1-1h-2z" />
                            </svg>
                        </button>
                    </div>
                    
                    <Link :href="route('tasks.create')" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Nuova Task
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- Filters Section -->
                <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <!-- Search -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cerca</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        id="search"
                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                        placeholder="Cerca per nome, progetto o cliente..."
                                    />
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stato</label>
                                <select
                                    v-model="statusFilter"
                                    id="status-filter"
                                    class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                >
                                    <option value="all">Tutti gli stati</option>
                                    <option value="da_fare">Da Fare</option>
                                    <option value="in_corso">In Corso</option>
                                    <option value="completato">Completato</option>
                                    <option value="in_pausa">In Pausa</option>
                                    <option value="in_attesa">In Attesa</option>
                                </select>
                            </div>

                            <!-- Project Filter -->
                            <div>
                                <label for="project-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Progetto</label>
                                <select
                                    v-model="projectFilter"
                                    id="project-filter"
                                    class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 sm:text-sm"
                                >
                                    <option value="all">Tutti i progetti</option>
                                    <option v-for="project in uniqueProjects" :key="project.id" :value="project.id">
                                        {{ project.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Active Filters & Reset -->
                        <div class="mt-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center space-x-4">
                                <div v-if="searchQuery || statusFilter !== 'all' || projectFilter !== 'all'" class="text-sm text-gray-600 dark:text-gray-400">
                                    Mostrando {{ filteredTasks.length }} di {{ tasks.length }} task
                                </div>
                                
                                <!-- Toggle Task Completate -->
                                <button
                                    @click="toggleCompleted"
                                    type="button"
                                    :class="showCompleted ? 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700' : 'bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600'"
                                    class="inline-flex items-center px-3 py-1.5 border rounded-full text-sm font-medium hover:shadow-sm transition-all"
                                >
                                    <svg v-if="showCompleted" class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg v-else class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ showCompleted ? '✓ Completate visibili' : 'Nascondi completate' }}
                                </button>
                            </div>
                            <button
                                @click="resetFilters"
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                            >
                                <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                Reset filtri
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Kanban View -->
                <div v-if="viewMode === 'kanban' && filteredTasks.length > 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <TaskKanban :tasks="filteredTasks" />
                </div>

                <!-- Table View -->
                <div v-else-if="viewMode === 'list' && filteredTasks.length > 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nome Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Progetto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Scadenza</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Stato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <tr v-for="task in filteredTasks" :key="task.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <Link :href="route('tasks.show', task.id)" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ task.name }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('projects.show', task.project.id)" class="text-sm text-gray-900 hover:text-gray-700 dark:text-gray-300">
                                            {{ task.project.name }}
                                        </Link>
                                        <div class="text-xs text-gray-500">{{ task.project.project_type.name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <Link :href="route('clients.show', task.project.contact.id)" class="text-sm text-gray-900 hover:text-gray-700 dark:text-gray-300">
                                            {{ task.project.contact.name }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatDate(task.deadline) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span :class="getStatusBadge(task.status)" class="inline-flex rounded-full border px-2 text-xs font-semibold leading-5">
                                            {{ getStatusLabel(task.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- Quick Actions -->
                                            <button
                                                v-if="task.status === 'da_fare'"
                                                @click="startTask(task.id)"
                                                class="inline-flex items-center p-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition-colors"
                                                title="Avvia"
                                            >
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                v-if="task.status === 'in_corso'"
                                                @click="completeTask(task.id)"
                                                class="inline-flex items-center p-1.5 rounded-md bg-green-600 text-white hover:bg-green-700 transition-colors"
                                                title="Completa"
                                            >
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            
                                            <!-- View & Edit -->
                                            <Link :href="route('tasks.show', task.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400" title="Vedi">
                                                Vedi
                                            </Link>
                                            <Link :href="route('tasks.edit', task.id)" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400" title="Modifica">
                                                Modifica
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ tasks.length === 0 ? 'Nessuna task' : 'Nessuna task trovata' }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ tasks.length === 0 ? 'Inizia creando una nuova task.' : 'Prova a modificare i filtri di ricerca.' }}
                        </p>
                        <div class="mt-6">
                            <Link v-if="tasks.length === 0" :href="route('tasks.create')" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Crea Task
                            </Link>
                            <button v-else @click="resetFilters" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                Reset Filtri
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

