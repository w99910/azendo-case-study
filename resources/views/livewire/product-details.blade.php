<div class="h-full bg-gray-100 pt-[5vh] pb-[10vh] overflow-y-auto">
    <div x-data="{ expanded: @entangle('editing') }" x-bind:class="expanded ? 'max-w-4xl' : 'max-w-3xl'"
        class="mx-auto bg-white rounded-lg relative shadow-lg overflow-hidden transition-all duration-500"
        x-transition:enter="transition-all ease-in-out duration-500" x-transition:enter-start="scale-95 opacity-50"
        x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transition-all ease-in-out duration-500"
        x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-50">
        <a href="/" class="absolute top-3 left-2  text-gray-600 px-3 py-2 rounded-lg flex items-center z-10">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-left-icon w-4 h-4 inline-block lucide-chevron-left">
                <path d="m15 18-6-6 6-6" />
            </svg>
            <span class="ml-1">Back to Products</span>
        </a>
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-200 flex items-center justify-center px-8 py-16">
                <img src="{{ $product->image ?? 'https://picsum.photos/640/480?random=' . $product->id }}"
                    alt="{{ $product->name }}" class="rounded-lg w-full h-80 object-cover">
            </div>
            <div class="md:w-1/2 p-6 flex flex-col justify-between">
                <div>
                    @if ($editing)
                        <div class="mb-2">
                            <label class="block text-gray-600 font-semibold mb-1">Name:</label>
                            <input type="text" wire:model="name"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-600 font-semibold mb-1">Price:</label>
                            <input type="number" step="0.01" wire:model="price"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-600 font-semibold mb-1">Stock:</label>
                            <input type="number" wire:model="stock"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-600 font-semibold mb-1">Category:</label>
                            <select wire:model="category_id"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none">
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-600 font-semibold mb-1">Brand:</label>
                            <select wire:model="brand_id"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-600 font-semibold mb-1">Serial Number:</label>
                            <input type="text" wire:model="serial_number"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm font-mono transition focus:outline-none" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-600 font-semibold mb-1">Description:</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-600 font-semibold mb-1">Status:</label>
                            <select wire:model="is_active"
                                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    @else
                        <h2 class="text-3xl font-bold mb-2">{{ $product->name }}</h2>
                        <div class="flex items-center mb-2">
                            <span
                                class="text-lg font-bold text-blue-600 mr-4">฿{{ number_format($product->price, 2) }}</span>
                            <span
                                class="text-sm px-2 py-1 rounded-full @if ($product->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="text-gray-600">Stock:</span>
                            <span
                                class="font-semibold @if ($product->stock < 10) text-red-500 @endif">{{ $product->stock }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-gray-600">Category:</span>
                            <span class="font-semibold">{{ $product->category->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-gray-600">Brand:</span>
                            <span class="font-semibold">{{ $product->brand->name ?? '-' }}</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-gray-600">Serial Number:</span>
                            <span class="font-mono">{{ $product->serial_number }}</span>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-1">Description</h3>
                            <p class="text-gray-700">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
                <div class="mt-4">
                    @if ($editing)
                        <button wire:click="save"
                            class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded ml-2">Save</button>
                        <button wire:click="$set('editing', false)"
                            class="inline-block bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancel</button>
                    @else
                        <button wire:click="edit"
                            class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded ml-2">Edit</button>
                        <button wire:click="delete" wire:confirm="Are you sure you want to delete this product?"
                            class="inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded ml-2">Delete</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="p-6 border-t">
            <h3 class="text-xl font-bold mb-4">Reviews</h3>
            @if ($product->reviews->count())
                <div class="space-y-4 max-h-60 overflow-y-auto">
                    @foreach ($product->reviews as $review)
                        <div class="bg-gray-50 p-3 rounded shadow-sm">
                            <div class="flex items-center mb-1">
                                <span class="font-semibold text-blue-700 mr-2">Score: {{ $review->score }}</span>
                                <span class="text-xs text-gray-500">{{ $review->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="text-gray-700">{{ $review->comment }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No reviews yet.</p>
            @endif
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
