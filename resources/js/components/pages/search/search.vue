<template>
    <div class="container mx-auto py-4 min-h-screen">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">Products List</h1>
        </div>

        <!-- Filters Section -->
        <div
            class="backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 rounded-xl shadow-md p-6 mb-6 border border-zinc-200/50 dark:border-zinc-700/50">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                <!-- Search -->
                <div>
                    <label for="search"
                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Search</label>
                    <input type="text" id="search" v-model="filters.search"
                        placeholder="Search by product name or description..."
                        class="w-full placeholder:text-zinc-500 dark:placeholder:text-zinc-400 px-3 py-2 border bg-zinc-50/30 border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm">
                </div>
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Price Range</label>
                    <div class="flex gap-2">
                        <input type="number" v-model="filters.priceMin" placeholder="Minimum price"
                            class="w-full placeholder:text-zinc-500 dark:placeholder:text-zinc-400 px-3 py-2 border border-zinc-300 bg-zinc-50/30 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm">
                        <input type="number" v-model="filters.priceMax" placeholder="Maximum price"
                            class="w-full placeholder:text-zinc-500 dark:placeholder:text-zinc-400 px-3 py-2 border border-zinc-300 bg-zinc-50/30 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm">
                    </div>
                </div>
                <!-- Minimum Stock -->
                <div>
                    <label for="stockMin"
                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Minimum Stock</label>
                    <input type="number" id="stockMin" v-model="filters.stockMin" placeholder="Minimum stock"
                        class="w-full px-3 placeholder:text-zinc-500 dark:placeholder:text-zinc-400 py-2 border border-zinc-300 bg-zinc-50/30 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm">
                </div>
                <!-- Status -->
                <div>
                    <label for="status"
                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Status</label>
                    <select id="status" v-model="filters.status"
                        class="w-full px-3 py-2 border border-zinc-300 bg-zinc-50/30 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <!-- Categories -->
                <div class="lg:row-start-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Categories</label>
                    <div
                        class="max-h-32 overflow-y-auto border border-zinc-300 dark:border-zinc-600 rounded-md p-2 space-y-1 bg-zinc-50/30 dark:bg-zinc-700">
                        <div v-for="category in availableFilters.categories" :key="category.id"
                            class="flex items-center">
                            <input :id="'cat-' + category.id" type="checkbox" :value="category.id"
                                v-model="filters.categories"
                                class="h-4 w-4 text-blue-600 border-zinc-300 rounded focus:ring-blue-500">
                            <label :for="'cat-' + category.id"
                                class="ml-2 block text-sm text-zinc-900 dark:text-zinc-100">{{ category.name }}</label>
                        </div>
                    </div>
                </div>
                <!-- Brands -->
                <div class="lg:row-start-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Brands</label>
                    <div
                        class="max-h-32 overflow-y-auto border border-zinc-300 dark:border-zinc-600 rounded-md p-2 space-y-1 bg-zinc-50/30 dark:bg-zinc-700">
                        <div v-for="brand in availableFilters.brands" :key="brand.id" class="flex items-center">
                            <input :id="'brand-' + brand.id" type="checkbox" :value="brand.id" v-model="filters.brands"
                                class="h-4 w-4 text-blue-600 border-zinc-300 rounded focus:ring-blue-500">
                            <label :for="'brand-' + brand.id"
                                class="ml-2 block text-sm text-zinc-900 dark:text-zinc-100">{{ brand.name }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Toolbar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ products.length }} products
            </div>
            <div class="flex items-center gap-4 flex-wrap">
                <!-- View Toggle -->
                <div class="flex items-center gap-1">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">View:</span>
                    <button @click="viewMode = 'grid'"
                        :class="['px-3 py-1 rounded-md text-sm', viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600']">Grid</button>
                    <button @click="viewMode = 'table'"
                        :class="['px-3 py-1 rounded-md text-sm', viewMode === 'table' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600']">Table</button>
                </div>
                <!-- Per Page -->
                <div class="flex items-center gap-1">
                    <label for="perPage" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Per page:</label>
                    <select id="perPage" v-model="filters.perPage"
                        class="px-2 py-1 border border-zinc-300 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-zinc-100 text-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <!-- Sort By -->
                <div class="flex items-center gap-1">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Sort by:</span>
                    <button @click="setSort('name')"
                        :class="['px-3 py-1 rounded-md text-sm', filters.sortField === 'name' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600']">Name
                        {{ filters.sortField === 'name' ? (filters.sortDirection === 'asc' ? '↑' : '↓') : '' }}</button>
                    <button @click="setSort('price')"
                        :class="['px-3 py-1 rounded-md text-sm', filters.sortField === 'price' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600']">Price
                        {{ filters.sortField === 'price' ? (filters.sortDirection === 'asc' ? '↑' : '↓') : ''
                        }}</button>
                    <button @click="setSort('stock')"
                        :class="['px-3 py-1 rounded-md text-sm', filters.sortField === 'stock' ? 'bg-blue-600 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600']">Stock
                        {{ filters.sortField === 'stock' ? (filters.sortDirection === 'asc' ? '↑' : '↓') : ''
                        }}</button>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div v-show="loading" class="flex justify-center items-center py-16">
            <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-zinc-600 dark:text-zinc-400">Loading products...</span>
        </div>

        <!-- Product Grid -->
        <div v-if="!loading && viewMode === 'grid'"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="product in products" :key="product.id"
                class="backdrop-blur-2xl bg-gradient-to-br from-white/40 via-blue-100/30 to-blue-200/20 dark:from-zinc-800/40 dark:via-indigo-900/30 dark:to-zinc-900/20 rounded-lg shadow-md overflow-hidden flex flex-col border border-zinc-200/50 dark:border-zinc-700/50">
                <!-- Product Image Placeholder -->
                <div class="relative h-48 bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                    <img :src="product.image && product.image.startsWith('http') ? product.image : 'https://' + product.image"
                        alt="Product Image"
                        class="w-full hover:scale-105 transition-all duration-300 h-full object-cover">
                    <!-- <div class="w-full h-full bg-zinc-200 dark:bg-zinc-700"></div> -->
                    <span :class="[
                        'absolute top-2 left-2 px-2 py-0.5 rounded-full text-xs font-semibold',
                        product.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                    ]">
                        {{ product.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <!-- Product Info -->
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold mb-1 text-zinc-900 dark:text-zinc-50">{{ product.name }}</h3>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">฿{{ product.price
                            }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded"
                            :class="product.stock > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                            Stock: {{ product.stock }}
                        </span>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Serial: {{ product.serial }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-1">Category: <span
                            class="font-medium text-zinc-700 dark:text-zinc-300">{{ product.category.name }}</span></p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3">Brand: <span
                            class="font-medium text-zinc-700 dark:text-zinc-300">{{ product.brand.name }}</span></p>

                    <!-- Rating Placeholder -->
                    <div class="flex items-center gap-1 text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            :class="i <= calculateRating(product.reviews) ? 'text-yellow-400' : 'text-zinc-300 dark:text-zinc-600'"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-xs ml-1">({{ product.reviews.length }} reviews)</span>
                    </div>

                    <a :href="`/product/${product.id}`"
                        class="mt-auto cursor-pointer self-end bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                        Details
                    </a>
                </div>
            </div>
        </div>
        <!-- Product Table -->
        <div v-if="!loading && viewMode === 'table'"
            class="bg-white dark:bg-zinc-900 rounded-xl shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Image</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Name</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Category</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Brand</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Price</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Stock</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Serial</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                    <tr v-for="product in products" :key="product.id"
                        class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-4 py-2 whitespace-nowrap">
                            <img :src="product.image && product.image.startsWith('http') ? product.image : 'https://picsum.photos/40/40?random=' + product.id"
                                alt="Product Image" class="w-10 h-10 object-cover rounded">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{
                            product.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{
                            product.category?.name || 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{
                            product.brand?.name || 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">฿{{
                            product.price }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{
                            product.stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="[
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                product.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ]">
                                {{ product.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{
                            product.serial }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            <a :href="`/product/${product.id}`" class="text-blue-600 hover:text-blue-700">View</a>
                        </td>
                    </tr>
                    <tr v-if="products.length === 0">
                        <td colspan="9" class="px-6 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            No products found matching your criteria.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && pagination.totalPages > 1" class="mt-8 flex justify-end py-4">
            <div class="flex flex-wrap justify-center items-center space-x-1">
                <!-- Previous Button -->
                <button @click="changePage(pagination.currentPage - 1)" :disabled="pagination.currentPage <= 1"
                    class="px-3 py-1 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 font-semibold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                    &laquo;
                </button>

                <!-- Page Numbers -->
                <template v-for="(page, index) in paginationWindow" :key="index">
                    <span v-if="page === '...'" class="px-2 text-zinc-500 dark:text-zinc-400">...</span>
                    <button v-else @click="changePage(page)"
                        class="px-3 py-1 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 font-semibold shadow-sm transition"
                        :class="{ '!bg-blue-600 !text-white': page === pagination.currentPage }">
                        {{ page }}
                    </button>
                </template>

                <!-- Next Button -->
                <button @click="changePage(pagination.currentPage + 1)"
                    :disabled="pagination.currentPage >= pagination.totalPages"
                    class="px-3 py-1 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 font-semibold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch, computed } from 'vue';
import productApi from '../../../api/product'; // Assuming API file path
import categoryApi from '../../../api/category';
import brandApi from '../../../api/brand';
import Toaster from 'vanilla-toaster';
const viewMode = ref('grid'); // 'grid' or 'table'

const loading = ref(true);

const calculateRating = (ratings) => {
    let score = 0;
    for (let i = 0; i < ratings.length; i++) {
        score += ratings[i].score;
    }
    return score / ratings.length;
}

const filters = reactive({
    search: '',
    priceMin: null,
    priceMax: null,
    stockMin: null,
    status: 'all',
    categories: [],
    brands: [],
    sortField: 'name',
    sortDirection: 'asc',
    page: 1,
    perPage: 10,
});

const availableFilters = ref({
    categories: [],
    brands: []
});

const pagination = reactive({
    currentPage: 1,
    totalPages: 1 // This would be calculated based on total products
});

const products = ref([]);

// Function to handle sorting (basic implementation)
const setSort = (field) => {
    if (filters.sortField === field) {
        filters.sortDirection = filters.sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        filters.sortField = field;
        filters.sortDirection = 'asc';
    }
    getProducts();
};

const getProducts = async () => {
    loading.value = true;
    try {
        // TODO: Pass filters, sorting, pagination to the API call
        productApi.getProducts({ ...filters }).then((r) => {
            const [response, total] = r;
            products.value = response;
            pagination.totalPages = Math.ceil(total / filters.perPage);
        }).catch((error) => {
            console.error("Failed to fetch products:", error);
            Toaster.toast(error.response?.data?.message ?? 'Failed to fetch products', 'error');
        });
    } catch (error) {
        console.error("Failed to fetch products:", error);
        Toaster.toast(error.message, 'error');
        // Handle error display if needed
    } finally {
        loading.value = false;
    }
}

const getCategoriesAndBrands = async () => {
    const categories = await categoryApi.getCategories();
    const brands = await brandApi.getBrands();
    availableFilters.value.categories = categories;
    availableFilters.value.brands = brands;
}

getCategoriesAndBrands();

// Add computed properties or watchers here later to handle filtering, sorting, pagination logic

onMounted(() => {
    getProducts();
});

// --- Pagination Logic ---
const changePage = (newPage) => {
    if (newPage >= 1 && newPage <= pagination.totalPages && newPage !== pagination.currentPage) {
        pagination.currentPage = newPage;
        getProducts();
    }
};

// --- Pagination Window Logic ---
const windowSize = 2; // Corresponds to $side in PHP example
const paginationWindow = computed(() => {
    const current = pagination.currentPage;
    const last = pagination.totalPages;
    const delta = windowSize;

    const range = [];
    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }

    const pages = [];
    pages.push(1); // Always show first page

    if (current - delta > 2) { // Ellipsis after first page
        pages.push('...');
    }

    pages.push(...range);

    if (current + delta < last - 1) { // Ellipsis before last page
        pages.push('...');
    }

    if (last > 1) { // Always show last page if > 1 page total
        pages.push(last);
    }

    // Remove duplicates that might occur if range includes 1 or last
    return [...new Set(pages)];
});

let timeout;

// Watch filters (excluding page/perPage/sort which are handled separately or don't reset page)
watch(() => ({
    search: filters.search,
    priceMin: filters.priceMin,
    priceMax: filters.priceMax,
    stockMin: filters.stockMin,
    status: filters.status,
    categories: filters.categories,
    brands: filters.brands,
}), () => {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(() => {
        pagination.currentPage = 1; // Reset to page 1 on filter change
        getProducts();
    }, 400);
});

// Watch perPage changes separately
watch(() => filters.perPage, (newPerPage) => {
    // No need to sync filters.perPage here as getProducts reads from pagination.perPage
    pagination.currentPage = 1; // Reset page
    getProducts();
});

</script>

<style scoped>
/* Style scrollbars for webkit browsers */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
    /* Or use a background like bg-zinc-100 dark:bg-zinc-800 */
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: rgba(160, 160, 160, 0.5);
    /* zinc-400/50 */
    border-radius: 3px;
    border: 1px solid transparent;
    background-clip: content-box;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: rgba(113, 113, 122, 0.5);
    /* zinc-500/50 */
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: rgba(140, 140, 140, 0.7);
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: rgba(130, 130, 137, 0.7);
}

/* Firefox scrollbar styling (optional) */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: rgba(160, 160, 160, 0.5) transparent;
}

.dark .overflow-y-auto {
    scrollbar-color: rgba(113, 113, 122, 0.5) transparent;
}
</style>