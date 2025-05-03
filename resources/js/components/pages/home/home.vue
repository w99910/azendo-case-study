<template>
    <div class="py-8 container mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- For You Section -->
            <section class="col-span-1 lg:col-span-2 rounded-xl p-2">
                <h2 class="text-2xl font-bold mb-6 text-zinc-800 dark:text-zinc-100">For You</h2>
                <div v-if="personalizedLoading" class="text-center py-10">Loading personalized products...</div>
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <div v-for="product in personalizedProducts" :key="product.id"
                        class="backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 rounded-lg shadow-md overflow-hidden flex flex-col border border-zinc-200/50 dark:border-zinc-700/50">
                        <!-- Product Image Placeholder -->
                        <div class="relative h-48 bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                            <img :src="product.image" alt="Product Image"
                                class="w-full hover:scale-105 transition-all duration-300 h-full object-cover" />
                            <span :class="[
                                'absolute top-2 left-2 px-2 py-0.5 rounded-full text-xs font-semibold',
                                product.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ]">
                                {{ product.status === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <!-- Product Info -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold mb-1 text-zinc-900 dark:text-zinc-50">{{ product.name }}
                            </h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">฿{{
                                    product.price
                                }}</span>
                                <span class="text-xs font-medium px-2 py-0.5 rounded"
                                    :class="product.stock > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                                    Stock: {{ product.stock }}
                                </span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Serial: {{ product.serial }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Category: <span
                                    class="font-medium text-zinc-700 dark:text-zinc-300">{{ product.category }}</span>
                            </p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3">Brand: <span
                                    class="font-medium text-zinc-700 dark:text-zinc-300">{{ product.brand }}</span></p>

                            <!-- Rating Placeholder -->
                            <div class="flex items-center gap-1 text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                                <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                    :class="i <= product.rating ? 'text-yellow-400' : 'text-zinc-300 dark:text-zinc-600'"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-xs ml-1">({{ product.reviews }} reviews)</span>
                            </div>

                            <button
                                class="mt-auto w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Right Column -->
            <div class="col-span-1 flex flex-col gap-6 py-2">
                <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">Featured</h2>
                <section
                    class="bg-gradient-to-br overflow-hidden  from-purple-500 to-indigo-600 text-white rounded-xl  shadow-lg  flex flex-col md:flex-row items-center">
                    <template v-if="productOfTodayLoading">
                        <div class="w-full h-48 bg-zinc-200 dark:bg-zinc-700 animate-pulse rounded-lg mb-4"></div>
                        <div class="w-3/4 h-6 bg-zinc-200 dark:bg-zinc-700 animate-pulse rounded mb-2"></div>
                        <div class="w-full h-4 bg-zinc-200 dark:bg-zinc-700 animate-pulse rounded mb-2"></div>
                        <div class="w-1/2 h-6 bg-zinc-200 dark:bg-zinc-700 animate-pulse rounded"></div>
                    </template>
                    <template v-else>
                        <img :src="productOfToday.image" alt="Product of Today"
                            class="w-1/2 h-full object-cover hover:scale-105 transition-all duration-300 rounded-lg" />
                        <div class="text-center md:text-left p-4">
                            <h3 class="text-sm font-medium uppercase tracking-wider mb-1 text-indigo-200">Product Of
                                Today
                            </h3>
                            <div class="text-xl font-bold mb-1">{{ productOfToday.name }}</div>
                            <div class="text-indigo-100 text-sm mb-3">{{ productOfToday.description.slice(0, 100) }}...
                            </div>
                            <button
                                class="bg-white text-indigo-600 hover:bg-indigo-50 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                Learn More
                            </button>
                        </div>
                    </template>
                </section>
                <!-- Popular Products -->
                <section
                    class="backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 rounded-xl shadow-lg p-6 flex flex-col border border-zinc-200/50 dark:border-zinc-700/50">
                    <h3 class="text-xl font-semibold mb-4 text-zinc-800 dark:text-zinc-100">Popular Products</h3>
                    <div v-if="popularLoading" class="text-center py-10">Loading popular products...</div>
                    <div v-else class="flex-1 space-y-4">
                        <div v-for="product in popularProducts" :key="product.id"
                            class="backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 flex items-center gap-4 p-3 rounded-md border border-zinc-200/50 dark:border-zinc-700/50">
                            <img :src="product.image" alt="Product Image" class="w-10 h-10 object-cover rounded-md" />
                            <div class="flex-1">
                                <div class="font-medium text-sm text-zinc-900 dark:text-zinc-50">{{ product.name }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ product.category }}</div>
                            </div>
                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">฿{{ product.price
                                }}</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import product from '../../../api/product';

const personalizedProducts = ref([]);
const personalizedLoading = ref(true);
const productOfToday = ref({});
const productOfTodayLoading = ref(true);
const popularProducts = ref([]);
const popularLoading = ref(true);

onMounted(async () => {
    const response = await product.getProductsForYou();
    personalizedProducts.value = response;
    personalizedLoading.value = false;

    const productOfTodayResponse = await product.getProductOfTheDay();
    productOfToday.value = productOfTodayResponse;
    productOfTodayLoading.value = false;

    const popularProductsResponse = await product.getPopularProducts();
    popularProducts.value = popularProductsResponse;
    popularLoading.value = false;
});
</script>