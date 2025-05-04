<div x-data="{
    selectedView: @entangle('selectedView'),
    showCreateModal: false,
    selectedProducts: [],
    toggleProduct(id) {
        const idx = this.selectedProducts.indexOf(id.toString());
        if (idx > -1) {
            this.selectedProducts.splice(idx, 1);
        } else {
            this.selectedProducts.push(id.toString());
        }
    },
    selectAll() {
        this.selectedProducts = Array.from(document.querySelectorAll('[data-product-id]')).map(el => el.getAttribute('data-product-id'));
    },
    deselectAll() {
        this.selectedProducts = [];
    },
}" class="mx-auto px-4 py-6 h-full w-full flex items-center justify-center overflow-y-auto">
    <div class="h-full container">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-3xl font-bold mb-6">Products List</h1>
            <button @click="showCreateModal = true"
                class="px-5 py-2 rounded-lg border border-blue-500 bg-blue-500 hover:bg-blue-600 text-white font-semibold shadow transition">
                + Add Product
            </button>
        </div>
        <div class="mb-6 bg-white p-4 rounded shadow">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Search:</label>
                    <input wire:change="updatePage" type="text" wire:model="search"
                        class="w-full focus:outline-none border border-gray-300 rounded-lg p-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition placeholder-gray-400"
                        placeholder="Type what you want to search...">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Price Range:</label>
                    <div class="flex space-x-2">
                        <input wire:change="updatePage" type="number" wire:model="priceMin"
                            class="w-full focus:outline-none border border-gray-300 rounded-lg p-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition placeholder-gray-400"
                            placeholder="Minimum price">
                        <input wire:change="updatePage" type="number" wire:model="priceMax"
                            class="w-full focus:outline-none border border-gray-300 rounded-lg p-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition placeholder-gray-400"
                            placeholder="Maximum price">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Minimum Stock:</label>
                    <input wire:change="updatePage" type="number" wire:model="stockMin"
                        class="w-full focus:outline-none border border-gray-300 rounded-lg p-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition placeholder-gray-400"
                        placeholder="Minimum stock">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Status:</label>
                    <select wire:change="updatePage" wire:model="isActive"
                        class="w-full focus:outline-none border border-gray-300 rounded-lg p-2 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition text-gray-700">
                        <option value="">All</option>
                        <option value="1">Active only</option>
                        <option value="0">Inactive only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Categories:</label>
                    <div class="h-40 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50">
                        @foreach ($categories as $category)
                            <div class="mb-1">
                                <label class="inline-flex items-center">
                                    <input wire:change="updatePage" type="checkbox" wire:model="selectedCategories"
                                        value="{{ $category->id }}"
                                        class="mr-2 focus:outline-none rounded border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <span class="text-gray-700">{{ $category->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Brands:</label>
                    <div class="h-40 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50">
                        @foreach ($brands as $brand)
                            <div class="mb-1">
                                <label class="inline-flex items-center">
                                    <input wire:change="updatePage" type="checkbox" wire:model="selectedBrands"
                                        value="{{ $brand->id }}"
                                        class="mr-2 focus:outline-none rounded border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <span class="text-gray-700">{{ $brand->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div wire:loading class="w-full flex justify-center my-8">
                <div class="spinner">Loading...</div>
            </div>

            <div wire:loading.remove>
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center mb-4">
                        <span class="font-bold">{{ number_format($totalProducts) }} products</span>
                        <button @click.prevent="selectAll()"
                            class="ml-4 px-3 py-1 rounded-lg border border-gray-400 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition">Select
                            All</button>
                        <button x-cloak x-show="(selectedProducts || []).length > 0" @click.prevent="deselectAll()"
                            class="ml-2 px-3 py-1 rounded-lg border border-gray-400 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition">Deselect</button>
                        <button x-cloak x-show="(selectedProducts || []).length > 0"
                            @click.prevent="
                                if((selectedProducts || []).length && confirm('Delete selected products?')) {
                                    $wire.deleteSelected(selectedProducts);
                                    deselectAll();
                                }
                            "
                            class="ml-2 px-3 py-1 rounded-lg border border-red-400 bg-red-50 hover:bg-red-100 text-red-700 font-semibold shadow-sm transition">Delete</button>

                    </div>
                    <div>
                        <span class="mr-2">View:</span>
                        <button wire:click="setView('grid')"
                            class="px-3 py-1 rounded-lg border border-blue-400 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold shadow-sm transition @if ($selectedView === 'grid') bg-blue-600 text-white @endif">
                            grid
                        </button>
                        <button wire:click="setView('table')"
                            class="px-3 py-1 rounded-lg border border-blue-400 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold shadow-sm transition @if ($selectedView === 'table') bg-blue-600 text-white @endif">
                            table
                        </button>
                        <!-- Per Page Selector -->
                        <label class="ml-3 font-semibold text-gray-700" for="perPage">Per page:</label>
                        <select id="perPage" wire:model="perPage" wire:change="updatePage"
                            class="mx-2 px-3 py-1.5 rounded-lg border focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-2 border-gray-300 bg-white text-gray-700 font-semibold shadow-sm transition">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="mr-2">Sort by:</span>
                        <button wire:click="sortBy('name')"
                            class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition @if ($sortField === 'name') !bg-blue-500 text-white @endif">
                            Name
                            @if ($sortField === 'name')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </button>
                        <button wire:click="sortBy('price')"
                            class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition @if ($sortField === 'price') !bg-blue-500 text-white @endif">
                            Price
                            @if ($sortField === 'price')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </button>
                        <button wire:click="sortBy('stock')"
                            class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition @if ($sortField === 'stock') !bg-blue-500 text-white @endif">
                            Stock
                            @if ($sortField === 'stock')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </button>
                    </div>
                </div>

                <div x-show="selectedView === 'grid'">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($products as $product)
                            <div data-product-id="{{ $product->id }}"
                                @click.ctrl.prevent="toggleProduct('{{ $product->id }}')"
                                :class="(selectedProducts || []).includes('{{ $product->id }}') ?
                                    'ring-2 ring-blue-500 border-blue-500' : 'ring-0 border-gray-200'"
                                class="bg-white rounded-xl  overflow-hidden shadow-lg hover:shadow-2xl transition-shadow relative cursor-pointer flex flex-col h-full group">
                                <div class="relative">
                                    <img class="w-full h-48 object-cover rounded-t-xl group-hover:scale-105 transition-transform duration-300"
                                        src="{{ $product->image }}" alt="{{ $product->name }}">
                                    <span
                                        class="absolute top-3 left-3 px-2 py-1 text-xs rounded-full font-semibold @if ($product->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex-1 flex flex-col justify-between p-4">
                                    <div>
                                        <h3 class="text-xl font-bold mb-1 truncate" title="{{ $product->name }}">
                                            {{ $product->name }}</h3>
                                        <div class="flex items-center mb-2 space-x-2">
                                            <span
                                                class="text-lg font-bold text-blue-600">฿{{ number_format($product->price, 2) }}</span>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">Stock:
                                                <span
                                                    class="font-semibold @if ($product->stock < 10) text-red-500 @endif">{{ $product->stock }}</span></span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 mb-2 text-xs text-gray-500">
                                            <span>Serial: <span
                                                    class="font-mono">{{ $product->serial_number }}</span></span>
                                            <span>Category: {{ $product->category->name }}</span>
                                            <span>Brand: {{ $product->brand->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-yellow-500">★</span>
                                            <span
                                                class="font-semibold text-sm">{{ number_format($product->score, 1) }}</span>
                                            <span class="text-xs text-gray-400">({{ $product->reviews->count() }}
                                                reviews)</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-end">
                                        <a href="/admin/products/{{ $product->id }}" target="_blank"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow transition">Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="selectedView === 'table'">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border"></th>
                                <th class="py-2 px-4 border">Serial</th>
                                <th class="py-2 px-4 border">Image</th>
                                <th class="py-2 px-4 border">Product</th>
                                <th class="py-2 px-4 border">Category</th>
                                <th class="py-2 px-4 border">Brand</th>
                                <th class="py-2 px-4 border">Price</th>
                                <th class="py-2 px-4 border">Stock</th>
                                <th class="py-2 px-4 border">Rating</th>
                                <th class="py-2 px-4 border">Status</th>
                                <th class="py-2 px-4 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                @php
                                    $score = 0;
                                    foreach ($product->reviews as $value) {
                                        $score += $value->score;
                                    }
                                    $product->score = $score ? $score / $product->reviews->count() : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border">
                                        <input type="checkbox"
                                            :checked="(selectedProducts || []).includes('{{ $product->id }}')"
                                            @change="toggleProduct('{{ $product->id }}')"
                                            class="w-5 h-5 rounded-full border-2 border-gray-300 focus:ring-2 transition">
                                    </td>
                                    <td class="py-2 px-4 border">{{ $index + 1 + ($page - 1) * $perPage }}</td>
                                    <td class="py-2 px-4 border">
                                        <div class="flex items-center">
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                class="w-10 h-10 mr-2 rounded">
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 border">{{ $product->name }}</td>
                                    <td class="py-2 px-4 border">{{ $product->category->name }}</td>
                                    <td class="py-2 px-4 border">{{ $product->brand->name }}</td>
                                    <td class="py-2 px-4 border font-bold">฿{{ number_format($product->price, 2) }}
                                    </td>
                                    <td
                                        class="py-2 px-4 border @if ($product->stock < 10) text-red-500 font-bold @endif">
                                        {{ $product->stock }}
                                    </td>
                                    <td class="py-2 px-4 border">{{ $product->score }}</td>

                                    <td class="py-2 px-4 border">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs @if ($product->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border">
                                        <a href="/admin/products/{{ $product->id }}" target="_blank"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Fixed Pagination -->
                @if ($lastPage > 1)
                    <div class="flex justify-end py-8">
                        <div class="flex flex-wrap justify-center items-center space-x-1">
                            @php
                                $side = 2;
                                $window = $side * 2;
                                $start = max(1, $page - $side);
                                $end = min($lastPage, $page + $side);
                                if ($page <= $window) {
                                    $end = min($lastPage, 1 + $window);
                                }
                                if ($page > $lastPage - $window) {
                                    $start = max(1, $lastPage - $window);
                                }
                            @endphp
                            <!-- Previous Button -->
                            <button wire:click="goToPage({{ $page - 1 }})"
                                @if ($page == 1) disabled @endif
                                class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                                &laquo;
                            </button>
                            <!-- First Page -->
                            <button wire:click="goToPage(1)"
                                class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition @if ($page == 1) !bg-blue-500 text-white @endif">
                                1
                            </button>
                            @if ($start > 2)
                                <span class="px-2">...</span>
                            @endif
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i != 1 && $i != $lastPage)
                                    <button wire:click="goToPage({{ $i }})"
                                        class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition @if ($page == $i) !bg-blue-500 text-white @endif">
                                        {{ $i }}
                                    </button>
                                @endif
                            @endfor
                            @if ($end < $lastPage - 1)
                                <span class="px-2">...</span>
                            @endif
                            @if ($lastPage > 1)
                                <button wire:click="goToPage({{ $lastPage }})"
                                    class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition @if ($page == $lastPage) !bg-blue-500 text-white @endif">
                                    {{ $lastPage }}
                                </button>
                            @endif
                            <!-- Next Button -->
                            <button wire:click="goToPage({{ $page + 1 }})"
                                @if ($page == $lastPage) disabled @endif
                                class="px-3 py-1 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-semibold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                                &raquo;
                            </button>
                        </div>
                    </div>
                @endif


            </div>
        </div>

        <div class="fixed inset-0 z-50 flex items-start justify-center h-screen w-screen overflow-y-auto py-[5vh] bg-[rgba(0,0,0,0.4)]"
            style="display: block;" x-show="showCreateModal" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-cloak @closemodal="showCreateModal = false">
            <livewire:product-create-modal @productcreated="syncProducts" />
        </div>

    </div>
</div>

@script
    <script>
        $js('toast', ({
            message,
            type
        }) => {
            window.dispatchEvent(new CustomEvent('showtoast', {
                detail: {
                    type: type ?? 'info',
                    message: message ?? 'test'
                }
            }));
        });
    </script>
@endscript
