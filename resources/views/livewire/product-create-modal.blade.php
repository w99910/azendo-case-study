<div x-data class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-8 relative animate-fade-in-up"
    @click.outside="$dispatch('closemodal')">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Add New Product</h2>
    <form @submit.prevent="$nextTick(() => $wire.createProduct())">
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Name:</label>
            <input type="text" wire:model.defer="newProduct.name"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none" />
            @error('newProduct.name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Price (฿):</label>
            <input type="number" step="0.01" wire:model.defer="newProduct.price"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none" />
            @error('newProduct.price')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Stock:</label>
            <input type="number" wire:model.defer="newProduct.stock"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none" />
            @error('newProduct.stock')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Category:</label>
            <select wire:model.defer="newProduct.category_id"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none">
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('newProduct.category_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Brand:</label>
            <select wire:model.defer="newProduct.brand_id"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none">
                <option value="">Select Brand</option>
                @foreach ($brands as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
            @error('newProduct.brand_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Serial Number:</label>
            <input type="text" wire:model.defer="newProduct.serial_number"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm font-mono transition focus:outline-none" />
            @error('newProduct.serial_number')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Description:</label>
            <textarea wire:model.defer="newProduct.description" rows="3"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none"></textarea>
            @error('newProduct.description')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="block text-gray-600 font-semibold mb-1">Status:</label>
            <select wire:model.defer="newProduct.is_active"
                class="w-full border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-lg p-2 shadow-sm transition focus:outline-none">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            @error('newProduct.is_active')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-end mt-6 space-x-2">
            <button type="button" @click="$nextTick(() => $dispatch('closemodal'))"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold shadow">Cancel</button>
            <button type="submit"
                class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-semibold shadow">Create</button>
        </div>
    </form>
</div>

@script
    <script>
        $js('close', () => {
            $dispatch('closemodal');
        });
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
