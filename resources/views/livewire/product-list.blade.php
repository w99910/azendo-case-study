<div>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6">Products List</h1>
        <div class="mb-6 bg-white p-4 rounded shadow">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-2">Search:</label>
                    <input type="text" wire:model="search" class="w-full border rounded p-2"
                        placeholder="Type what you want to search...">
                </div>

                <div>
                    <label class="block mb-2">Price Range:</label>
                    <div class="flex space-x-2">
                        <input type="text" wire:model="priceMin" class="w-full border rounded p-2"
                            placeholder="Minimum price">
                        <input type="text" wire:model="priceMax" class="w-full border rounded p-2"
                            placeholder="Maximum price">
                    </div>
                </div>

                <div>
                    <label class="block mb-2">Minimum Stock:</label>
                    <input type="text" wire:model="stockMin" class="w-full border rounded p-2"
                        placeholder="Minimum stock">
                </div>

                <div>
                    <label class="block mb-2">Status:</label>
                    <select wire:model="isActive" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <option value="1">Active only</option>
                        <option value="0">Inactive only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block mb-2">Categories:</label>
                    <div class="h-40 overflow-y-auto border rounded p-2">
                        @foreach ($categories as $category)
                            <div class="mb-1">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                                        class="mr-2">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block mb-2">Brands:</label>
                    <div class="h-40 overflow-y-auto border rounded p-2">
                        @foreach ($brands as $brand)
                            <div class="mb-1">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="selectedBrands" value="{{ $brand->id }}"
                                        class="mr-2">
                                    {{ $brand->name }}
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
                    <div>
                        <span class="font-bold">{{ number_format($totalProducts) }} products</span>
                    </div>
                    <div>
                        <span class="mr-2">View:</span>
                        <button wire:click="setView('grid')"
                            class="px-3 py-1 rounded border @if ($selectedView === 'grid') bg-blue-600 text-white @endif">
                            grid view
                        </button>
                        <button wire:click="setView('table')"
                            class="px-3 py-1 rounded border @if ($selectedView === 'table') bg-blue-600 text-white @endif">
                            table view
                        </button>
                        |
                        <span class="mr-2">Sort by:</span>
                        <button wire:click="sortBy('name')"
                            class="px-3 py-1 rounded border @if ($sortField === 'name') bg-blue-500 text-white @endif">
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
                            class="px-3 py-1 rounded border @if ($sortField === 'price') bg-blue-500 text-white @endif">
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
                            class="px-3 py-1 rounded border @if ($sortField === 'stock') bg-blue-500 text-white @endif">
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

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 if @if ($selectedView !== 'grid') hidden @endif">
                    @foreach ($products as $product)
                        @php
                            $Category = App\Models\Category::where('id', $product->category_id)->get();
                            $Brand = App\Models\Brand::where('id', $product->brand_id)->get();
                            $Review = App\Models\Review::where('product_id', $product->id);
                            $score = 0;
                            foreach ($Review->get() as $value) {
                                $score += $value->score;
                            }
                            $product->score = $Review->count() == 0 ? 0 : $score / $Review->count();
                            $product->category = $Category[0];
                            $product->brand = $Brand[0];
                        @endphp
                        <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2">{{ $product->name }}</h3>
                                Rating : {{ $score }}
                                <img class="round-lg" src="{{ $product->image }}">
                                <div class="text-xs text-gray-500 mb-2">
                                    <span class="block">rating: {{ $product->score }}</span>
                                    <span class="block">Serial: {{ $product->serial_number }}</span>
                                    <span class="block">Category: {{ $product->category->name }}</span>
                                    <span class="block">Brand: {{ $product->brand->name }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-lg">฿{{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm @if ($product->stock < 10) text-red-500 @endif">
                                        Stock: {{ $product->stock }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="rounded-full px-2 py-1 text-xs @if ($product->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="@if ($selectedView !== 'table') hidden @endif">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr class="bg-gray-100">
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
                                    $Category = App\Models\Category::where('id', $product->category_id)->get();
                                    $Brand = App\Models\Brand::where('id', $product->brand_id)->get();
                                    $Review = App\Models\Review::where('product_id', $product->id);
                                    $score = 0;
                                    foreach ($Review->get() as $value) {
                                        $score += $value->score;
                                    }
                                    $product->score = $Review->count() == 0 ? 0 : $score / $Review->count();
                                    $product->category = $Category[0];
                                    $product->brand = $Brand[0];
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border">{{ $index + 1 + ($currentPage - 1) * $perPage }}</td>
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
                                        <button
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Fixed Pagination -->
                @if ($lastPage > 1)
                    <div class="flex justify-center mt-8">
                        <div class="flex flex-wrap justify-center items-center space-x-1">
                            <!-- Previous Button -->
                            @if ($currentPage > 1)
                                <button wire:click="goToPage({{ $currentPage - 1 }})"
                                    class="px-3 py-1 rounded border hover:bg-gray-100">
                                    &laquo;
                                </button>
                            @endif

                            <!-- First Page -->
                            <button wire:click="goToPage(1)"
                                class="px-3 py-1 rounded border @if ($currentPage === 1) bg-blue-500 text-white @else hover:bg-gray-100 @endif">
                                1
                            </button>

                            <!-- Ellipsis if needed -->
                            @if ($currentPage > 4)
                                <span class="px-3 py-1">...</span>
                            @endif

                            <!-- Pages around current page -->
                            @for ($i = max(2, $currentPage - 2); $i <= min($lastPage - 1, $currentPage + 2); $i++)
                                <button wire:click="goToPage({{ $i }})"
                                    class="px-3 py-1 rounded border @if ($currentPage === $i) bg-blue-500 text-white @else hover:bg-gray-100 @endif">
                                    {{ $i }}
                                </button>
                            @endfor

                            <!-- Ellipsis if needed -->
                            @if ($currentPage < $lastPage - 3)
                                <span class="px-3 py-1">...</span>
                            @endif

                            <!-- Last Page (if more than 1 page) -->
                            @if ($lastPage > 1)
                                <button wire:click="goToPage({{ $lastPage }})"
                                    class="px-3 py-1 rounded border @if ($currentPage === $lastPage) bg-blue-500 text-white @else hover:bg-gray-100 @endif">
                                    {{ $lastPage }}
                                </button>
                            @endif

                            <!-- Next Button -->
                            @if ($currentPage < $lastPage)
                                <button wire:click="goToPage({{ $currentPage + 1 }})"
                                    class="px-3 py-1 rounded border hover:bg-gray-100">
                                    &raquo;
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
