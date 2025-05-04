<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use App\Services\ProductService;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

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
    public $isActive = '';
    public $stockMin = '';
    public $page = 1;
    public $lastPage = 1;
    public $selectedView = 'grid';
    public $perPage = 50;
    public $selectedProducts = [];

    public $newProduct = [
        'name' => '',
        'price' => '',
        'stock' => '',
        'category_id' => '',
        'brand_id' => '',
        'serial_number' => '',
        'description' => '',
        'is_active' => 1,
    ];
    public $createCategories = [];
    public $createBrands = [];

    public Collection $products;
    public int $totalProducts = 0;

    public function __construct()
    {
        $this->products = collect([]);
    }

    public function updatePage()
    {
        $this->syncProducts();
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->syncProducts();
    }

    public function setView($view)
    {
        $this->selectedView = $view;
        // $this->resetPage();
        $this->syncProducts();
    }

    public function syncProducts($clear = false)
    {
        $key = json_encode(get_object_vars($this));

        if ($clear) {
            Cache::forget('products-' . $key);
        }

        [$productsWithRelations, $totalProducts] = Cache::remember('products-' . $key, 60, function () {
            $productService = new ProductService();

            try {
                return $productService->searchProducts([
                    'search' => $this->search,
                    'priceMin' => $this->priceMin,
                    'priceMax' => $this->priceMax,
                    'stockMin' => $this->stockMin,
                    'categories' => $this->selectedCategories,
                    'brands' => $this->selectedBrands,
                    'sortField' => $this->sortField,
                    'sortDirection' => $this->sortDirection,
                    'isActive' => $this->isActive,
                    'page' => $this->page,
                    'perPage' => $this->perPage,
                ]);
            } catch (\Exception $e) {
                $this->js('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error',
                ]);

                return [null, null];
            }
        });

        if ($productsWithRelations) {
            $this->products = $productsWithRelations; // Corrected assignment
            $this->totalProducts = $totalProducts;
            $this->lastPage = ceil($totalProducts / $this->perPage);
        }
    }

    public function mount()
    {
        $this->syncProducts();
    }

    public function render()
    {
        $categories = Cache::remember('categories', 60 * 5, function () {
            return Category::all();
        });

        $brands = Cache::remember('brands', 60 * 5, function () {
            return Brand::all();
        });

        return view('livewire.product-list', [
            'categories' => $categories,
            'brands' => $brands,
            'perPage' => $this->perPage,
        ]);
    }

    public function goToPage($page)
    {
        $this->page = $page;
        $this->syncProducts();
    }

    public function toggleProductSelection($productId)
    {
        if (($key = array_search($productId, $this->selectedProducts)) !== false) {
            unset($this->selectedProducts[$key]);
        } else {
            $this->selectedProducts[] = $productId;
        }
    }

    public function selectAll()
    {
        $this->selectedProducts = $this->products->pluck('id')->toArray();
    }

    public function clearSelection()
    {
        $this->selectedProducts = [];
    }

    public function deleteSelected($ids = null)
    {
        // $idsToDelete = $ids ?? $this->selectedProducts;
        // Product::whereIn('id', $idsToDelete)->delete();
        $this->clearSelection();
        $this->syncProducts(true);
        $this->js('toast', [
            'message' => 'Selected products deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function deselectAll()
    {
        $this->clearSelection();
    }
}