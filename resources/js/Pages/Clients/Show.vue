<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    client: {
        type: Object,
        required: true
    }
});

const formatCurrency = (value) => {
    if (!value) return '-';
    return new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR' }).format(value);
};
</script>

<template>
    <Head :title="client.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ client.name }}
                </h2>
                <Link :href="route('clients.edit', client.id)" class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700">
                    Modifica
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- Breadcrumb -->
                <nav class="mb-6 flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('clients.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400">
                                Clienti
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ client.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Informazioni Cliente -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                📞 Informazioni Cliente
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div v-if="client.accordo_economico_mensile">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">💰 MRR (Monthly Recurring Revenue)</dt>
                                    <dd class="mt-1 text-2xl font-bold text-green-600">{{ formatCurrency(client.accordo_economico_mensile) }}</dd>
                                </div>
                                <div v-if="client.tipo_fatturazione">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">📅 Tipo Fatturazione</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-300 capitalize">{{ client.tipo_fatturazione }}</dd>
                                </div>
                                <div v-if="client.metodo_pagamento">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">💳 Metodo Pagamento</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-300 capitalize">{{ client.metodo_pagamento }}</dd>
                                </div>
                                <div v-if="client.data_prossima_fattura">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">📆 Prossima Fattura</dt>
                                    <dd class="mt-1 text-sm font-semibold text-indigo-600">{{ new Date(client.data_prossima_fattura).toLocaleDateString('it-IT') }}</dd>
                                </div>
                                <div class="flex items-center space-x-4 pt-2">
                                    <div v-if="client.ghl_attivo" class="flex items-center">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                            ✅ GHL Attivo
                                        </span>
                                    </div>
                                    <div v-if="client.whatsapp_attivo" class="flex items-center">
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                            ✅ WhatsApp Attivo
                                        </span>
                                    </div>
                                </div>
                                <div v-if="client.potenziale_upsell" class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">🚀 Potenziale Upsell</dt>
                                    <dd class="mt-1 text-lg font-semibold text-orange-600">{{ formatCurrency(client.potenziale_upsell) }}/mese</dd>
                                    <p v-if="client.note_upsell" class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ client.note_upsell }}</p>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Progetti -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                📁 Progetti ({{ client.projects?.length || 0 }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div v-if="client.projects && client.projects.length > 0" class="space-y-4">
                                <div v-for="project in client.projects" :key="project.id" class="rounded-lg border border-gray-200 p-4 dark:border-gray-700 hover:border-indigo-300 transition-colors">
                                    <Link :href="route('projects.show', project.id)" class="text-base font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ project.name }}
                                    </Link>
                                    <div class="mt-2 flex items-center space-x-2">
                                        <span class="inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800">
                                            {{ project.project_type.name }}
                                        </span>
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800">
                                            {{ project.tasks.length }} task
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500 dark:text-gray-400">Nessun progetto associato</p>
                        </div>
                    </div>
                </div>

                <!-- Strategia Upsell & Note Commerciali -->
                <div id="upsell" class="mt-6 overflow-hidden bg-gradient-to-br from-orange-50 to-orange-100 shadow-lg sm:rounded-lg dark:from-gray-800 dark:to-gray-700 border-2 border-orange-400 dark:border-orange-500 animate-fadeIn">
                    <div class="border-b border-orange-300 bg-gradient-to-r from-orange-100 to-orange-200 p-6 dark:border-orange-600 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-600 shadow-lg">
                                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-orange-900 dark:text-orange-300">
                                        Strategia Commerciale & Upsell
                                    </h3>
                                    <p class="text-sm text-orange-700 dark:text-orange-400">
                                        Piano di crescita per questo cliente
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div v-if="client.potenziale_upsell" class="text-right">
                                    <p class="text-xs text-orange-800 dark:text-orange-300 font-medium">Potenziale Mensile</p>
                                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">+{{ formatCurrency(client.potenziale_upsell) }}</p>
                                </div>
                                <Link :href="`${route('clients.edit', client.id)}#upsell`" 
                                      class="inline-flex items-center rounded-lg border-2 border-orange-600 bg-orange-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-orange-700 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all transform hover:scale-105">
                                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifica Upsell
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- MRR Attuale vs Potenziale - Prominente -->
                        <div class="rounded-xl bg-white p-6 dark:bg-gray-800 border-2 border-orange-300 dark:border-orange-600 shadow-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div class="text-center p-4 rounded-lg bg-green-50 dark:bg-green-900/20">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">💰 MRR Attuale</p>
                                    <p class="text-3xl font-bold text-green-600">{{ formatCurrency(client.accordo_economico_mensile) }}</p>
                                </div>
                                
                                <div class="flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="h-12 w-12 text-orange-500 mx-auto animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                        <p class="mt-2 text-sm font-bold text-orange-600">CRESCITA</p>
                                    </div>
                                </div>
                                
                                <div class="text-center p-4 rounded-lg bg-orange-50 dark:bg-orange-900/20 border-2 border-orange-400 dark:border-orange-500">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">🎯 MRR Potenziale</p>
                                    <p class="text-3xl font-bold text-orange-600">{{ formatCurrency((client.accordo_economico_mensile || 0) + (client.potenziale_upsell || 0)) }}</p>
                                </div>
                            </div>
                            
                            <div v-if="client.potenziale_upsell" class="mt-6 pt-4 border-t-2 border-orange-200 dark:border-orange-700">
                                <div class="flex items-center justify-between p-4 rounded-lg bg-gradient-to-r from-orange-100 to-orange-50 dark:from-orange-900/30 dark:to-orange-900/10">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">📈 Incremento Potenziale</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Se chiuso l'upsell proposto</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold text-orange-600">+{{ Math.round(((client.potenziale_upsell / (client.accordo_economico_mensile || 1)) * 100)) }}%</p>
                                        <p class="text-lg font-semibold text-orange-700 dark:text-orange-400">+{{ formatCurrency(client.potenziale_upsell) }}/mese</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Note Strategiche Upsell -->
                        <div v-if="client.note_upsell" class="rounded-lg bg-white p-4 dark:bg-gray-800 border border-orange-200 dark:border-gray-600">
                            <h4 class="flex items-center text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Piano Upsell
                            </h4>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ client.note_upsell }}</p>
                            </div>
                        </div>

                        <!-- Esigenze Specifiche -->
                        <div v-if="client.esigenze_specifiche" class="rounded-lg bg-white p-4 dark:bg-gray-800 border border-orange-200 dark:border-gray-600">
                            <h4 class="flex items-center text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Note Operative & Esigenze
                            </h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ client.esigenze_specifiche }}</p>
                        </div>

                        <!-- Dettagli Accordo -->
                        <div v-if="client.dettagli_accordo" class="rounded-lg bg-white p-4 dark:bg-gray-800 border border-orange-200 dark:border-gray-600">
                            <h4 class="flex items-center text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Dettagli Contratto Attuale
                            </h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ client.dettagli_accordo }}</p>
                        </div>

                        <!-- Livello Soddisfazione -->
                        <div class="rounded-lg bg-white p-4 dark:bg-gray-800 border border-orange-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">📊 Soddisfazione Cliente</h4>
                                    <div class="flex items-center space-x-3">
                                        <span v-if="client.livello_soddisfazione" class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium"
                                              :class="{
                                                  'bg-red-100 text-red-800': client.livello_soddisfazione === 'molto_insoddisfatto' || client.livello_soddisfazione === 'insoddisfatto',
                                                  'bg-gray-100 text-gray-800': client.livello_soddisfazione === 'neutrale',
                                                  'bg-green-100 text-green-800': client.livello_soddisfazione === 'soddisfatto' || client.livello_soddisfazione === 'molto_soddisfatto'
                                              }">
                                            {{ client.livello_soddisfazione === 'molto_insoddisfatto' ? '😞 Molto Insoddisfatto' :
                                               client.livello_soddisfazione === 'insoddisfatto' ? '😕 Insoddisfatto' :
                                               client.livello_soddisfazione === 'neutrale' ? '😐 Neutrale' :
                                               client.livello_soddisfazione === 'soddisfatto' ? '😊 Soddisfatto' :
                                               client.livello_soddisfazione === 'molto_soddisfatto' ? '🤩 Molto Soddisfatto' : '-' }}
                                        </span>
                                        <span v-if="client.nps_score !== null" class="inline-flex items-center rounded-full px-3 py-1 text-sm font-bold"
                                              :class="{
                                                  'bg-red-100 text-red-800': client.nps_score <= 6,
                                                  'bg-yellow-100 text-yellow-800': client.nps_score >= 7 && client.nps_score <= 8,
                                                  'bg-green-100 text-green-800': client.nps_score >= 9
                                              }">
                                            NPS: {{ client.nps_score }}/10
                                        </span>
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
