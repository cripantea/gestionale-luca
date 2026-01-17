<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const isOpen = ref(false);
const searchQuery = ref('');
const searchResults = ref({
    clients: [],
    projects: [],
    tasks: [],
});
const loading = ref(false);
const selectedIndex = ref(0);

// Simula ricerca (sostituisci con chiamata API real)
const performSearch = async () => {
    if (searchQuery.value.length < 2) {
        searchResults.value = { clients: [], projects: [], tasks: [] };
        return;
    }

    loading.value = true;

    // Qui dovresti fare una vera chiamata API
    // Per ora simuliamo con timeout
    setTimeout(() => {
        const query = searchQuery.value.toLowerCase();
        
        // Mock data - sostituisci con vera ricerca
        searchResults.value = {
            clients: [
                { id: 1, name: 'Mario Rossi', company: 'Rossi SRL', type: 'client' },
                { id: 2, name: 'Studio Dental', company: null, type: 'client' },
            ].filter(c => 
                c.name.toLowerCase().includes(query) || 
                (c.company && c.company.toLowerCase().includes(query))
            ),
            projects: [
                { id: 1, name: 'Sito Web Mario', client: 'Mario Rossi', type: 'project' },
                { id: 2, name: 'Social Media Studio', client: 'Studio Dental', type: 'project' },
            ].filter(p => p.name.toLowerCase().includes(query)),
            tasks: [
                { id: 1, name: 'Redesign Homepage', project: 'Sito Web Mario', type: 'task' },
                { id: 2, name: 'Post Facebook', project: 'Social Media Studio', type: 'task' },
            ].filter(t => t.name.toLowerCase().includes(query)),
        };
        
        loading.value = false;
    }, 300);
};

// Debounced search
let searchTimeout;
watch(searchQuery, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(performSearch, 300);
});

// Shortcut Cmd+K / Ctrl+K
const handleKeydown = (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        isOpen.value = !isOpen.value;
    }
    if (e.key === 'Escape' && isOpen.value) {
        closeSearch();
    }
};

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
});

const closeSearch = () => {
    isOpen.value = false;
    searchQuery.value = '';
    searchResults.value = { clients: [], projects: [], tasks: [] };
    selectedIndex.value = 0;
};

const allResults = computed(() => {
    const results = [];
    searchResults.value.clients.forEach(c => results.push({ ...c, category: 'clients' }));
    searchResults.value.projects.forEach(p => results.push({ ...p, category: 'projects' }));
    searchResults.value.tasks.forEach(t => results.push({ ...t, category: 'tasks' }));
    return results;
});

const navigateToResult = (result) => {
    let route = '';
    switch (result.category) {
        case 'clients':
            route = 'clients.show';
            break;
        case 'projects':
            route = 'projects.show';
            break;
        case 'tasks':
            route = 'tasks.show';
            break;
    }
    router.visit(window.route(route, result.id));
    closeSearch();
};

const totalResults = computed(() => 
    searchResults.value.clients.length + 
    searchResults.value.projects.length + 
    searchResults.value.tasks.length
);
</script>

<template>
    <!-- Trigger Button (Navbar) -->
    <button
        @click="isOpen = true"
        type="button"
        class="flex items-center space-x-2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
    >
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <span>Cerca...</span>
        <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
            ⌘K
        </kbd>
    </button>

    <!-- Search Modal -->
    <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isOpen"
            class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm"
            @click="closeSearch"
        ></div>
    </Transition>

    <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="isOpen"
            class="fixed inset-x-4 top-20 z-50 mx-auto max-w-2xl"
            @click.stop
        >
            <div class="overflow-hidden rounded-lg bg-white shadow-2xl dark:bg-gray-800">
                <!-- Search Input -->
                <div class="flex items-center border-b border-gray-200 dark:border-gray-700 px-4">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cerca clienti, progetti, task..."
                        class="flex-1 border-0 bg-transparent px-4 py-4 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 dark:text-gray-100 dark:placeholder-gray-500"
                        autofocus
                    />
                    <svg v-if="loading" class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Results -->
                <div
                    v-if="searchQuery.length >= 2"
                    class="max-h-96 overflow-y-auto p-2"
                >
                    <!-- No Results -->
                    <div
                        v-if="!loading && totalResults === 0"
                        class="py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                    >
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2">Nessun risultato trovato</p>
                    </div>

                    <!-- Clients -->
                    <div v-if="searchResults.clients.length > 0" class="mb-4">
                        <div class="px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            👤 CLIENTI ({{ searchResults.clients.length }})
                        </div>
                        <button
                            v-for="client in searchResults.clients"
                            :key="`client-${client.id}`"
                            @click="navigateToResult({ ...client, category: 'clients' })"
                            class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        >
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ client.name }}</div>
                            <div v-if="client.company" class="text-xs text-gray-500 dark:text-gray-400">{{ client.company }}</div>
                        </button>
                    </div>

                    <!-- Projects -->
                    <div v-if="searchResults.projects.length > 0" class="mb-4">
                        <div class="px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            📁 PROGETTI ({{ searchResults.projects.length }})
                        </div>
                        <button
                            v-for="project in searchResults.projects"
                            :key="`project-${project.id}`"
                            @click="navigateToResult({ ...project, category: 'projects' })"
                            class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        >
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ project.name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ project.client }}</div>
                        </button>
                    </div>

                    <!-- Tasks -->
                    <div v-if="searchResults.tasks.length > 0">
                        <div class="px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            ✅ TASK ({{ searchResults.tasks.length }})
                        </div>
                        <button
                            v-for="task in searchResults.tasks"
                            :key="`task-${task.id}`"
                            @click="navigateToResult({ ...task, category: 'tasks' })"
                            class="w-full rounded-md px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        >
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ task.name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ task.project }}</div>
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="mt-2">Inizia a digitare per cercare...</p>
                    <p class="mt-1 text-xs">Minimo 2 caratteri</p>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-2 text-xs text-gray-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <kbd class="px-1.5 py-0.5 font-semibold bg-white border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">↑</kbd>
                            <kbd class="ml-1 px-1.5 py-0.5 font-semibold bg-white border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">↓</kbd>
                            <span class="ml-2">per navigare</span>
                        </div>
                        <div>
                            <kbd class="px-1.5 py-0.5 font-semibold bg-white border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">ESC</kbd>
                            <span class="ml-2">per chiudere</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
