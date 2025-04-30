<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $priceMin = '';
    public $priceMax = '';
    public $selectedCategories = [];
    public $selectedBrands = [];
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $isActive = true;
    public $stockMin = '';
    public $page = 1;
    public $selectedView = 'grid';

    protected $queryString = [];

    public function sortBy($field)
    {
        $this->sortField = $field;
    }

    public function setView($view)
    {
        $this->selectedView = $view;
        $this->resetPage();
    }

    public function render()
    {
        $allProducts = Product::all();

        $filteredProducts = $allProducts->filter(function ($product) {
            $matchesSearch = empty($this->search) ||
                str_contains(strtolower($product->name), strtolower($this->search)) ||
                str_contains(strtolower($product->description), strtolower($this->search));

            $matchesCategory = empty($this->selectedCategories) ||
                in_array($product->category_id, $this->selectedCategories);

            $matchesBrand = empty($this->selectedBrands) ||
                in_array($product->brand_id, $this->selectedBrands);


            $matchesStock = empty($this->stockMin) || $product->stock >= $this->stockMin;

            return $matchesSearch && $matchesCategory &&
                $matchesBrand && $matchesStock;
        });

        $sortedProducts = $filteredProducts->sortBy(
            $this->sortField,
            SORT_REGULAR,
            $this->sortDirection === 'desc'
        );

        $perPage = 50;
        $productsOnPage = $sortedProducts->slice(($this->page - 1) * $perPage, $perPage);

        $categories = Category::all();
        $brands = Brand::all();

        $productsWithRelations = $productsOnPage->map(function ($product) {
            $product->image = "https://picsum.photos/640/480?random=" . $product->id;
            return $product;
        });


        return view('livewire.product-list', [
            'products' => $productsWithRelations,
            'categories' => $categories,
            'brands' => $brands,
            'totalProducts' => $sortedProducts->count(),
            'currentPage' => $this->page,
            'perPage' => $perPage,
            'lastPage' => ceil($sortedProducts->count() / $perPage),
        ]);
    }

    public function goToPage($page)
    {
        $this->page = $page;
    }
}