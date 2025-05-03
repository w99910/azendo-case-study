<template>
    <div :class="[
        global.data.isDark
            ? 'dark text-[#f4f4f5] bg-gradient-to-br from-[#09090b] via-[#18181b] to-[#312e81]'
            : 'text-[#18181b] bg-gradient-to-br from-[#e0e7ff] via-[#c7d2fe] to-[#818cf8]',
        'font-[Inter] h-screen flex flex-col overflow-hidden w-screen transition-colors duration-300'
    ]">
        <nav class="dark:text-blue-500 text-blue-900 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/" class="text-xl font-bold">Azendo</a>
                <button @click="toggleDark" class="rounded-full cursor-pointer p-2 transition-colors duration-200">
                    <svg v-if="!global.data.isDark" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-sun-icon lucide-sun">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2" />
                        <path d="M12 20v2" />
                        <path d="m4.93 4.93 1.41 1.41" />
                        <path d="m17.66 17.66 1.41 1.41" />
                        <path d="M2 12h2" />
                        <path d="M20 12h2" />
                        <path d="m6.34 17.66-1.41 1.41" />
                        <path d="m19.07 4.93-1.41 1.41" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-moon-icon lucide-moon">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                    </svg>
                </button>
            </div>
        </nav>
        <div class="flex-1 overflow-y-auto pb-20">
            <Transition name="fade" mode="out-in">
                <keep-alive>
                    <component :is="global.data.currentPage" />
                </keep-alive>
            </Transition>
        </div>
        <!-- Enhanced Dock -->
        <div class="fixed bottom-2 left-1/2 -translate-x-1/2 z-50">
            <div class="flex gap-6 items-center px-4 py-3 rounded-2xl shadow-2xl border border-white/20 dark:border-gray-700 bg-white/80 dark:bg-gray-900/60 backdrop-blur-lg ring-1 ring-black/10 dark:ring-white/10 transition-all duration-300"
                style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);">
                <DockButton icon="home" label="Home" :active="global.data.currentPage === 'home'"
                    @click="setDock('home')" />
                <DockButton icon="search" label="Search" :active="global.data.currentPage === 'search'"
                    @click="setDock('search')" />
                <DockButton icon="chat" label="Chat" :active="global.data.currentPage === 'chat'"
                    @click="setDock('chat')" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineComponent, onMounted, onBeforeMount } from 'vue';
import global from '../api/global';
import DockButton from './ui/DockButton.vue';

function toggleDark() {
    global.data.isDark = !global.data.isDark;
    document.documentElement.classList.toggle('dark', global.data.isDark);
}
function setDock(name) {
    global.data.currentPage = name;
}

onBeforeMount(() => {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (prefersDark) {
        document.documentElement.classList.add('dark');
    }

    global.data.isDark = prefersDark;
})
</script>

<style scoped>
/* Combined slide and fade transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(10px);
    /* Start slightly lower */
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
    /* End slightly higher */
}

/* Base state (optional but good practice) */
.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}

/* Extra dock shadow/glassmorphism for modern look */
</style>