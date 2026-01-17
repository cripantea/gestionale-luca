<script setup>
defineProps({
    rows: {
        type: Number,
        default: 5,
    },
    columns: {
        type: Number,
        default: 4,
    },
});
</script>

<template>
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="`header-${col}`"
                            class="px-6 py-3"
                        >
                            <div class="h-4 animate-pulse rounded bg-gray-200 dark:bg-gray-600"></div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="row in rows" :key="`row-${row}`">
                        <td
                            v-for="col in columns"
                            :key="`cell-${row}-${col}`"
                            class="px-6 py-4"
                        >
                            <div
                                class="h-4 animate-pulse rounded"
                                :class="col === 1 ? 'w-32 bg-gray-300 dark:bg-gray-600' : 'w-24 bg-gray-200 dark:bg-gray-700'"
                                :style="{ animationDelay: `${(row * col) * 50}ms` }"
                            ></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
@keyframes shimmer {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}

.animate-pulse {
    animation: shimmer 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
