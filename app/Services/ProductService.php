<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function getProduct(int $id): Product
    {
        return Product::with(['category', 'brand', 'reviews'])->findOrFail($id);
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function getProductsByIds(array $ids): Collection
    {
        return Product::whereIn('id', $ids)->get();
    }

    public function searchProducts(array $data): array
    {
        $query = Product::query()->with(['category', 'brand', 'reviews']);

        if (!empty($data['search'])) {
            $query->where(function ($q) use ($data) {
                $q->where('name', 'like', '%' . $data['search'] . '%')
                    ->orWhere('description', 'like', '%' . $data['search'] . '%');
            });
        }

        if (!empty($data['categories'])) {
            $query->whereIn('category_id', $data['categories']);
        }

        if (!empty($data['brands'])) {
            $query->whereIn('brand_id', $data['brands']);
        }

        if (!empty($data['stockMin'])) {
            $query->where('stock', '>=', (int) $data['stockMin']);
        }

        if (!empty($data['priceMin'])) {
            $query->where('price', '>=', (int) $data['priceMin']);
        }

        if (!empty($data['priceMax'])) {
            $query->where('price', '<=', (int) $data['priceMax']);
        }

        if (!empty($data['status']) && $data['status'] !== 'all') {
            $query->where('is_active', $data['status'] == 'active');
        }

        if (isset($data['isActive']) && $data['isActive'] !== '') {
            $query->where('is_active', (bool) $data['isActive']);
        }

        $sortField = $data['sortField'] ?? 'name';
        $sortDirection = $data['sortDirection'] ?? 'asc';
        $orderMethod = $sortDirection === 'desc' ? 'orderByDesc' : 'orderBy';
        $query->$orderMethod($sortField);

        $total = $query->count();

        if (isset($data['page']) && isset($data['perPage'])) {
            $offset = ($data['page'] - 1) * $data['perPage'];
            $products = $query->skip($offset)->take($data['perPage'])->get();
        } else {
            $products = $query->get();
        }

        return [$products, $total];
    }

    public function getProductOfTheDay(): Product
    {
        return Product::orderByRaw('RAND()')->first();
    }

    public function getPopularProducts(): Collection
    {
        return Product::where('is_active', true)->withCount('reviews')->orderByRaw('RAND()')->take(5)->get();
    }

    public function getProductsForYou(): Collection
    {
        return Product::where('is_active', true)->orderByRaw('RAND()')->take(5)->get();
    }
}
