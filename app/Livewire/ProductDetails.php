<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Cache;

class ProductDetails extends Component
{
    public $productId;
    public $name, $price, $stock, $category_id, $brand_id, $serial_number, $description, $is_active;
    public $editing = false;
    public $categories = [];
    public $brands = [];

    public function mount($id)
    {
        $this->productId = $id;
        $product = Product::with(['category', 'brand', 'reviews'])->findOrFail($this->productId);
        $this->fillProductFields($product);
        $this->categories = Cache::remember('categories', 60 * 5, function () {
            return Category::all();
        });
        $this->brands = Cache::remember('brands', 60 * 5, function () {
            return Brand::all();
        });
    }

    public function fillProductFields($product)
    {
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->serial_number = $product->serial_number;
        $this->description = $product->description;
        $this->is_active = $product->is_active;
    }

    public function render()
    {
        $product = Product::with(['category', 'brand', 'reviews'])->findOrFail($this->productId);
        return view('livewire.product-details', [
            'product' => $product,
        ]);
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function save()
    {
        $product = Product::findOrFail($this->productId);
        $product->name = $this->name;
        $product->price = $this->price;
        $product->stock = $this->stock;
        $product->category_id = $this->category_id;
        $product->brand_id = $this->brand_id;
        $product->serial_number = $this->serial_number;
        $product->description = $this->description;
        $product->is_active = $this->is_active;
        $product->save();
        $this->editing = false;
        $this->js('toast', [
            'message' => 'Product updated successfully.',
            'type' => 'success',
        ]);
    }

    public function delete()
    {
        $product = Product::findOrFail($this->productId);
        $product->delete();
        session()->flash('message', 'Product deleted successfully.');
        session()->flash('type', 'success');
        return redirect()->to('/');
    }
}