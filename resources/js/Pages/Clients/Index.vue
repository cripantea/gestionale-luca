<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    clients: Array,
});

// Filtri e ricerca
const searchQuery = ref('');

// Filtro dei clienti
const filteredClients = computed(() => {
    return props.clients.filter(client => {
        // Filtro ricerca
        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            const matchesName = client.name.toLowerCase().includes(query);
            const matchesEmail = client.email?.toLowerCase().includes(query);
            const matchesCompany = client.company?.toLowerCase().includes(query);
            const matchesPhone = client.phone?.toLowerCase().includes(query);
            if (!matchesName && !matchesEmail && !matchesCompany && !matchesPhone) return false;
        }

        return true;
    });
});

// Reset filtri
const resetFilters = () => {
    searchQuery.value = '';
};
</script>

<template>
    <Head title="Clienti" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Clienti
                </h2>
                <Link :href="route('clients.create')" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Nuovo Cliente
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- Filters Section -->
                <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cerca Cliente</label>
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
                                        placeholder="Cerca per nome, email, azienda o telefono..."
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters & Reset -->
                        <div v-if="searchQuery" class="mt-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Mostrando {{ filteredClients.length }} di {{ clients.length }} clienti
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

                <!-- Tabella clienti (Desktop) -->
                <div v-if="filteredClients.length > 0" class="hidden md:block overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">MRR</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Fatturazione</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Prossima Fattura</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <tr v-for="client in filteredClients" :key="client.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <Link :href="route('clients.show', client.id)" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ client.name }}
                                        </Link>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ client.company || '-' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span v-if="client.accordo_economico_mensile" class="font-semibold text-green-600">
                                            {{ new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR' }).format(client.accordo_economico_mensile) }}
                                        </span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <span v-if="client.tipo_fatturazione" class="capitalize">{{ client.tipo_fatturazione }}</span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <span v-if="client.data_prossima_fattura">
                                            {{ new Date(client.data_prossima_fattura).toLocaleDateString('it-IT', { day: 'numeric', month: 'short' }) }}
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <div class="flex items-center space-x-1">
                                            <span v-if="client.ghl_attivo" class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">GHL</span>
                                            <span v-if="client.whatsapp_attivo" class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">WA</span>
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">{{ client.projects_count || 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <Link :href="route('clients.show', client.id)" class="mr-3 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            Vedi
                                        </Link>
                                        <Link :href="route('clients.edit', client.id)" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400">
                                            Modifica
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card View (Mobile) -->
                <div v-if="filteredClients.length > 0" class="block md:hidden space-y-4">
                    <div
                        v-for="client in filteredClients"
                        :key="`card-${client.id}`"
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 hover:shadow-md transition-shadow"
                    >
                        <div class="p-4 space-y-3">
                            <!-- Header -->
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <Link
                                        :href="route('clients.show', client.id)"
                                        class="text-lg font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                                    >
                                        {{ client.name }}
                                    </Link>
                                    <p v-if="client.company" class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ client.company }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span v-if="client.ghl_attivo" class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">GHL</span>
                                    <span v-if="client.whatsapp_attivo" class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/50 dark:text-green-200">WA</span>
                                </div>
                            </div>

                            <!-- Info Grid -->
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">💰 MRR</div>
                                    <div v-if="client.accordo_economico_mensile" class="font-semibold text-green-600">
                                        {{ new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR' }).format(client.accordo_economico_mensile) }}
                                    </div>
                                    <div v-else class="text-gray-400">-</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">📁 Progetti</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ client.projects_count || 0 }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">📅 Fatturazione</div>
                                    <div v-if="client.tipo_fatturazione" class="capitalize text-gray-900 dark:text-gray-300">
                                        {{ client.tipo_fatturazione }}
                                    </div>
                                    <div v-else class="text-gray-400">-</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">🔄 Prossima</div>
                                    <div v-if="client.data_prossima_fattura" class="text-gray-900 dark:text-gray-300">
                                        {{ new Date(client.data_prossima_fattura).toLocaleDateString('it-IT', { day: 'numeric', month: 'short' }) }}
                                    </div>
                                    <div v-else class="text-gray-400">-</div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <Link
                                    :href="route('clients.show', client.id)"
                                    class="flex-1 inline-flex justify-center items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                >
                                    👁️ Vedi
                                </Link>
                                <Link
                                    :href="route('clients.edit', client.id)"
                                    class="flex-1 inline-flex justify-center items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
                                >
                                    ✏️ Modifica
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ clients.length === 0 ? 'Nessun cliente' : 'Nessun cliente trovato' }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ clients.length === 0 ? 'Inizia aggiungendo un nuovo cliente.' : 'Prova a modificare i filtri di ricerca.' }}
                        </p>
                        <div class="mt-6">
                            <Link v-if="clients.length === 0" :href="route('clients.create')" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Nuovo Cliente
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
