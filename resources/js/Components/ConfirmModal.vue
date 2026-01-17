<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Conferma Azione',
    },
    message: {
        type: String,
        required: true,
    },
    confirmText: {
        type: String,
        default: 'Conferma',
    },
    cancelText: {
        type: String,
        default: 'Annulla',
    },
    danger: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    details: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['confirm', 'cancel', 'update:show']);

const isOpen = ref(props.show);

watch(() => props.show, (newVal) => {
    isOpen.value = newVal;
});

const closeModal = () => {
    isOpen.value = false;
    emit('update:show', false);
    emit('cancel');
};

const confirmAction = () => {
    emit('confirm');
};
</script>

<template>
    <!-- Overlay -->
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
            @click="closeModal"
        ></div>
    </Transition>

    <!-- Modal -->
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
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div
                class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
                @click.stop
            >
                <!-- Icon -->
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full"
                    :class="danger ? 'bg-red-100 dark:bg-red-900/20' : 'bg-yellow-100 dark:bg-yellow-900/20'"
                >
                    <svg
                        v-if="danger"
                        class="h-6 w-6 text-red-600 dark:text-red-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    <svg
                        v-else
                        class="h-6 w-6 text-yellow-600 dark:text-yellow-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

                <!-- Title -->
                <div class="mt-3 text-center">
                    <h3
                        class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100"
                    >
                        {{ title }}
                    </h3>
                    
                    <!-- Message -->
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ message }}
                        </p>
                    </div>

                    <!-- Details List -->
                    <div
                        v-if="details.length > 0"
                        class="mt-4 rounded-md bg-gray-50 p-3 text-left dark:bg-gray-900/50"
                    >
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Questa azione eliminerà anche:
                        </p>
                        <ul class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                            <li v-for="(detail, index) in details" :key="index" class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>{{ detail }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Warning -->
                    <div
                        v-if="danger"
                        class="mt-4 rounded-md bg-red-50 border border-red-200 p-3 dark:bg-red-900/10 dark:border-red-800"
                    >
                        <p class="text-xs font-semibold text-red-800 dark:text-red-400 flex items-center justify-center">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Azione IRREVERSIBILE
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex space-x-3">
                    <button
                        type="button"
                        @click="closeModal"
                        :disabled="loading"
                        class="flex-1 inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                    >
                        {{ cancelText }}
                    </button>
                    <button
                        type="button"
                        @click="confirmAction"
                        :disabled="loading"
                        class="flex-1 inline-flex justify-center items-center rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        :class="danger 
                            ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' 
                            : 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500'"
                    >
                        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>
