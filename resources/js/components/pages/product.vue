<template>
    <div class="h-full  pt-[5vh] pb-[10vh] overflow-y-auto">
        <div v-if="!product" class="flex justify-center items-center h-96">
            <span class="text-lg text-red-500">Product not found.</span>
        </div>
        <div v-else
            class="mx-auto relative max-w-3xl transition-all duration-500 rounded-2xl shadow-lg overflow-hidden backdrop-blur-2xl border border-zinc-200/50 dark:border-zinc-700/50 bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20">
            <a href="#" @click.prevent="goBack"
                class="absolute top-3 left-2 text-blue-600 dark:text-blue-400 px-3 py-2 rounded-lg flex items-center z-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-left-icon w-4 h-4 inline-block lucide-chevron-left">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span class="ml-1">Back to Products</span>
            </a>
            <div class="flex flex-col md:flex-row">
                <div
                    class="md:w-1/2 bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 flex justify-center px-8 py-24">
                    <img :src="product.image || `https://picsum.photos/640/480?random=${product.id}`"
                        :alt="product.name" class="rounded-lg w-full h-80 object-cover">
                </div>
                <div class="md:w-1/2 p-6 flex flex-col justify-between">
                    <h2 class="text-3xl font-bold mb-2 text-zinc-900 dark:text-zinc-50">{{ product.name }}</h2>
                    <div class="flex items-center mb-2">
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400 mr-4">฿{{
                            formatPrice(product.price) }}</span>
                        <span
                            :class="['text-sm px-2 py-1 rounded-full', product.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200']">
                            {{ product.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600 dark:text-zinc-400">Stock:</span>
                        <span :class="['font-semibold', product.stock < 10 ? 'text-red-500' : '']">{{ product.stock
                        }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600 dark:text-zinc-400">Category:</span>
                        <span class="font-semibold">{{ product.category?.name || '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600 dark:text-zinc-400">Brand:</span>
                        <span class="font-semibold">{{ product.brand?.name || '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="text-gray-600 dark:text-zinc-400">Serial Number:</span>
                        <span class="font-mono">{{ product.serial_number }}</span>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-1 text-zinc-900 dark:text-zinc-100">Description</h3>
                        <div class="text-gray-700 dark:text-zinc-300"
                            v-show="product.description.length < 200 || showMore" v-html="product.description"></div>

                        <p v-show="product.description.length > 200 && !showMore"
                            class="text-gray-700 dark:text-zinc-300"
                            v-html="product.description.substring(0, 200) + ' .... '">
                        </p>
                        <p @click="showMore = !showMore" v-text="showMore ? 'Hide' : 'Read More'"
                            class="text-blue-600 underline dark:text-blue-400 cursor-pointer">

                        </p>
                    </div>
                    <div class="mt-4 self-end">
                        <a :href="product.product_link" target="_blank"
                            class="inline-block self-end bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded ml-2">Buy
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import MinimalLayout from '../layouts/minimal.vue'
import { ref } from 'vue';
defineOptions({ layout: MinimalLayout })

const props = defineProps({
    product: {
        type: Object,
        required: false,
        default: null
    }
});

const showMore = ref(false);

function goBack() {
    window.location.href = '/';
}

function formatPrice(price) {
    if (typeof price === 'number') {
        return price.toLocaleString(undefined, { minimumFractionDigits: 2 });
    }
    return price;
}
</script>
