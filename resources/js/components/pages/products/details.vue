<template>
    <div class="h-full bg-gray-100 pt-[5vh] pb-[10vh] overflow-y-auto">
        <div v-if="loading" class="flex justify-center items-center h-96">
            <span class="text-lg text-gray-500">Loading...</span>
        </div>
        <div v-else-if="product"
            class="mx-auto bg-white rounded-lg relative shadow-lg overflow-hidden max-w-3xl transition-all duration-500">
            <a href="#" @click.prevent="goBack"
                class="absolute top-3 left-2 text-blue-600 px-3 py-2 rounded-lg flex items-center z-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-left-icon w-4 h-4 inline-block lucide-chevron-left">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span class="ml-1">Back to Products</span>
            </a>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 bg-gray-200 flex items-center justify-center px-8 py-16">
                    <img :src="product.image || `https://picsum.photos/640/480?random=${product.id}`"
                        :alt="product.name" class="rounded-lg w-full h-80 object-cover">
                </div>
                <div class="md:w-1/2 p-6 flex flex-col justify-between">
                    <h2 class="text-3xl font-bold mb-2">{{ product.name }}</h2>
                    <div class="flex items-center mb-2">
                        <span class="text-lg font-bold text-blue-600 mr-4">฿{{ product.price.toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        }) }}</span>
                        <span
                            :class="['text-sm px-2 py-1 rounded-full', product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                            {{ product.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600">Stock:</span>
                        <span :class="['font-semibold', product.stock < 10 ? 'text-red-500' : '']">{{ product.stock
                        }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600">Category:</span>
                        <span class="font-semibold">{{ product.category?.name || '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600">Brand:</span>
                        <span class="font-semibold">{{ product.brand?.name || '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="text-gray-600">Serial Number:</span>
                        <span class="font-mono">{{ product.serial_number }}</span>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-1">Description</h3>
                        <div class="text-gray-700" v-html="product.description"></div>
                    </div>
                    <div class="mt-4">
                        <a :href="product.product_link" target="_blank"
                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded ml-2">Buy
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="flex justify-center items-center h-96">
            <span class="text-lg text-red-500">Product not found.</span>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import productApi from '../../../api/product';

const props = defineProps({
    id: {
        type: [String, Number],
        required: true
    }
});

const product = ref(null);
const loading = ref(true);

const fetchProduct = async () => {
    loading.value = true;
    try {
        product.value = await productApi.getProductById(props.id);
    } catch (e) {
        product.value = null;
    } finally {
        loading.value = false;
    }
};

onMounted(fetchProduct);
watch(() => props.id, fetchProduct);

function goBack() {
    // Implement navigation logic, e.g., emit event or update global state
    window.history.back();
}
</script>