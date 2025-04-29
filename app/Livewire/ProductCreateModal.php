<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;

class ProductCreateModal extends Component
{
    public $newProduct = [
        'name' => 'Product Name',
        'price' => '1000',
        'stock' => '100',
        'category_id' => '',
        'brand_id' => '',
        'serial_number' => '',
        'description' => 'Product Description',
        'is_active' => '0',
        'image' => 'https://picsum.photos/640/480?random=1',
    ];
    public $categories = [];
    public $brands = [];

    public function mount()
    {
        $this->init();
    }

    public function init()
    {
        $this->reset('newProduct');
        $this->newProduct['is_active'] = 1;
        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->newProduct['serial_number'] = Str::random(10);
        $this->newProduct['category_id'] = $this->categories[rand(0, count($this->categories) - 1)]->id;
        $this->newProduct['brand_id'] = $this->brands[rand(0, count($this->brands) - 1)]->id;
    }

    public function createProduct()
    {
        $validated = $this->validate([
            'newProduct.name' => 'required|string|max:255',
            'newProduct.price' => 'required|numeric|min:0',
            'newProduct.stock' => 'required|integer|min:0',
            'newProduct.category_id' => 'required|exists:categories,id',
            'newProduct.brand_id' => 'required|exists:brands,id',
            'newProduct.serial_number' => 'required|string|max:255',
            'newProduct.description' => 'nullable|string',
            'newProduct.is_active' => 'required|boolean',
            'newProduct.image' => 'string',
        ]);
        Product::create($validated['newProduct']);
        $this->js('close');
        $this->dispatch('productcreated');
        $this->js('toast', [
            'message' => 'Product created successfully.',
            'type' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.product-create-modal');
    }
}