<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    nome: '',
    importo_totale: '',
    frequenza: 'mensile',
    descrizione: '',
    categoria: '',
    data_prossimo_rinnovo: '',
    data_scadenza: '',
    metodo_pagamento: '',
    note: '',
});

const submit = () => {
    form.post(route('spese.store'));
};
</script>

<template>
    <Head title="Nuova Spesa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Nuova Spesa
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Servizio *</label>
                                <input v-model="form.nome" type="text" id="nome" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                       :class="{ 'border-red-500': form.errors.nome }">
                                <p v-if="form.errors.nome" class="mt-2 text-sm text-red-600">{{ form.errors.nome }}</p>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="importo_totale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Importo (€) *</label>
                                    <input v-model="form.importo_totale" type="number" step="0.01" id="importo_totale" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label for="frequenza" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequenza *</label>
                                    <select v-model="form.frequenza" id="frequenza" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="mensile">Mensile</option>
                                        <option value="bimensile">Bimensile</option>
                                        <option value="trimestrale">Trimestrale</option>
                                        <option value="annuale">Annuale</option>
                                        <option value="una_tantum">Una Tantum</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="descrizione" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrizione</label>
                                    <input v-model="form.descrizione" type="text" id="descrizione" placeholder="es. 9 domini, 6 account, etc."
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                    <select v-model="form.categoria" id="categoria"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="">- Seleziona -</option>
                                        <option value="tool">🛠️ Tool & Software</option>
                                        <option value="comunicazione">📱 Comunicazione</option>
                                        <option value="formazione">📚 Formazione</option>
                                        <option value="design">🎨 Design & Asset</option>
                                        <option value="fiscale">💼 Fiscale & Legale</option>
                                        <option value="tasse">🔴 Tasse</option>
                                        <option value="università">🎓 Università</option>
                                        <option value="altro">📦 Altro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Data Scadenza (Una Tantum) o Rinnovo (Ricorrente) -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div v-if="form.frequenza === 'una_tantum'">
                                    <label for="data_scadenza" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        📅 Data Scadenza *
                                    </label>
                                    <input v-model="form.data_scadenza" type="date" id="data_scadenza" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Quando devi pagare questa spesa</p>
                                </div>
                                
                                <div v-else>
                                    <label for="data_prossimo_rinnovo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        🔄 Prossimo Rinnovo
                                    </label>
                                    <input v-model="form.data_prossimo_rinnovo" type="date" id="data_prossimo_rinnovo"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label for="metodo_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metodo Pagamento</label>
                                    <select v-model="form.metodo_pagamento" id="metodo_pagamento"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="">- Seleziona -</option>
                                        <option value="carta">Carta</option>
                                        <option value="bonifico">Bonifico</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="stripe">Stripe</option>
                                        <option value="altro">Altro</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
                                <textarea v-model="form.note" id="note" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                            </div>

                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <Link :href="route('spese.index')" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    Annulla
                                </Link>
                                <button type="submit" :disabled="form.processing"
                                        class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 disabled:opacity-50">
                                    Aggiungi Spesa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
