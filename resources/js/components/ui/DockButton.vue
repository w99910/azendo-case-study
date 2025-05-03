<template>
    <button :class="[
        'w-12 h-12 flex flex-col items-center justify-center rounded-lg transition-all duration-200',
        active ? 'bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 dark:from-blue-900 dark:via-indigo-900 dark:to-indigo-800 scale-110 shadow-lg' : 'hover:bg-blue-50 dark:hover:bg-gray-700'
    ]">
        <span v-if="icon === 'home'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-house-icon lucide-house">
                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                <path
                    d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            </svg>
        </span>
        <span v-else-if="icon === 'search'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-search-icon lucide-search">
                <path d="m21 21-4.34-4.34" />
                <circle cx="11" cy="11" r="8" />
            </svg>
        </span>
        <span v-else-if="icon === 'chat'">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-bot-icon lucide-bot">
                <path d="M12 8V4H8" />
                <rect width="16" height="12" x="4" y="8" rx="2" />
                <path d="M2 14h2" />
                <path d="M20 14h2" />
                <path d="M15 13v2" />
                <path d="M9 13v2" />
            </svg>
        </span>
        <!-- <span class="text-xs font-medium">{{ label }}</span> -->
    </button>
</template>

<script setup>
const props = defineProps({
    icon: String,
    label: String,
    active: Boolean
});
</script>
