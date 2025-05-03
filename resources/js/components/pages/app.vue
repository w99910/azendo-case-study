<template>
    <Transition name="fade" mode="out-in">
        <keep-alive>
            <component :is="global.data.currentPage" />
        </keep-alive>
    </Transition>
</template>

<script setup>
import global from '../../api/global';
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