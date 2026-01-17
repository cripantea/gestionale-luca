<script setup>
defineProps({
    count: {
        type: Number,
        default: 3,
    },
});
</script>

<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
            v-for="n in count"
            :key="`skeleton-${n}`"
            class="overflow-hidden rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
        >
            <div class="space-y-4">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div class="h-6 w-32 animate-pulse rounded bg-gray-200 dark:bg-gray-700" :style="{ animationDelay: `${n * 100}ms` }"></div>
                    <div class="h-6 w-16 animate-pulse rounded bg-gray-200 dark:bg-gray-700" :style="{ animationDelay: `${n * 100 + 50}ms` }"></div>
                </div>
                
                <!-- Content -->
                <div class="space-y-2">
                    <div class="h-4 w-full animate-pulse rounded bg-gray-100 dark:bg-gray-700" :style="{ animationDelay: `${n * 100 + 100}ms` }"></div>
                    <div class="h-4 w-3/4 animate-pulse rounded bg-gray-100 dark:bg-gray-700" :style="{ animationDelay: `${n * 100 + 150}ms` }"></div>
                </div>
                
                <!-- Footer -->
                <div class="flex space-x-2 pt-2">
                    <div class="h-8 w-20 animate-pulse rounded bg-gray-200 dark:bg-gray-700" :style="{ animationDelay: `${n * 100 + 200}ms` }"></div>
                    <div class="h-8 w-20 animate-pulse rounded bg-gray-200 dark:bg-gray-700" :style="{ animationDelay: `${n * 100 + 250}ms` }"></div>
                </div>
            </div>
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
