<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isOpen = ref(false);

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const actions = [
    {
        label: 'Nuova Task',
        href: 'tasks.create',
        icon: '✅',
        color: 'bg-blue-600 hover:bg-blue-700',
    },
    {
        label: 'Nuovo Cliente',
        href: 'clients.create',
        icon: '👤',
        color: 'bg-green-600 hover:bg-green-700',
    },
    {
        label: 'Nuovo Progetto',
        href: 'projects.create',
        icon: '📁',
        color: 'bg-purple-600 hover:bg-purple-700',
    },
];
</script>

<template>
    <!-- Overlay (chiude menu quando clicchi fuori) -->
    <div
        v-if="isOpen"
        @click="toggleMenu"
        class="fixed inset-0 z-40"
    ></div>

    <!-- FAB Container -->
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Action Buttons (espansi) -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="isOpen" class="mb-4 flex flex-col space-y-3">
                <Link
                    v-for="(action, index) in actions"
                    :key="action.href"
                    :href="route(action.href)"
                    @click="toggleMenu"
                    class="group flex items-center space-x-3 transition-all"
                    :style="{ animationDelay: `${index * 50}ms` }"
                >
                    <span
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium text-white shadow-lg opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900"
                    >
                        {{ action.label }}
                    </span>
                    <button
                        type="button"
                        class="flex h-12 w-12 items-center justify-center rounded-full text-white shadow-lg transition-all transform hover:scale-110"
                        :class="action.color"
                    >
                        <span class="text-xl">{{ action.icon }}</span>
                    </button>
                </Link>
            </div>
        </Transition>

        <!-- Main FAB Button -->
        <button
            @click="toggleMenu"
            type="button"
            class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg transition-all transform hover:scale-110 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            :class="{ 'rotate-45': isOpen }"
        >
            <svg
                class="h-6 w-6 transition-transform"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>
        </button>
    </div>
</template>

<style scoped>
.rotate-45 {
    transform: rotate(45deg);
}
</style>
