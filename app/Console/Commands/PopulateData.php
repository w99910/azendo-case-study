<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Services\OllamaService;
use App\Services\QdrantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PopulateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('This might take a while... Please be patient.');


        $collectionName = 'products';
        $points = [];
        $payloads = [];

        $existingProductIds = Product::orderBy('id', 'desc')->pluck('id')->toArray();
        $newProductIds = [];

        $startIndex = $existingProductIds[0] ?? 0;

        $ollama = new OllamaService();
        $qdrant = new QdrantService(collectionName: $collectionName);
        $syncPointsAndPayloads = function () use (&$points, &$payloads, $collectionName, &$startIndex, $qdrant) {
            if (empty($points)) {
                return;
            }

            $this->info('Syncing points and payloads...');
            $qdrant->addVectors($collectionName, $points, $payloads, $startIndex);

            $startIndex += count($points);
            $points = [];
            $payloads = [];
        };

        $this->info("Found " . count($existingProductIds) . " existing products.");
        $this->info('Fetching products data...');

        $products = Http::get('https://makeup-api.herokuapp.com/api/v1/products.json')->json();

        $this->info('Populating products data...');
        $bar = $this->output->createProgressBar(count($products));
        foreach ($products as $product) {
            // We don't want to populate data for existing products
            if (in_array($product['id'], $existingProductIds)) {
                $bar->advance();
                continue;
            }

            if (empty($product['brand']) || empty($product['category'])) {
                $bar->advance();
                continue;
            }

            $brand = Brand::firstOrCreate([
                'name' => $product['brand'],
                'description' => $product['brand'],
            ]);

            $category = Category::firstOrCreate([
                'name' => $product['category'],
                'description' => $product['category'],
            ]);

            $_product = Product::create([
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => ((int) $product['price'] * 34) ?? rand(100, 1000),
                'stock' => rand(0, 100),
                'is_active' => rand(0, 1) == 1,
                'serial_number' => Str::uuid(),
                'image' => isset($product['api_featured_image']) ?
                    'https://' . str_replace("//", "", $product['api_featured_image'])
                    : "https://picsum.photos/640/480?random=" . rand(1, 1000),
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'attributes' => $product['product_colors'],
                'product_link' => $product['product_link'],
            ]);

            $embeddings = $ollama->embed($_product->name . ' ' . $_product->description);

            $points[] = $embeddings['embeddings'][0];
            $payloads[] = [
                'id' => $_product->id,
                'name' => $_product->name,
                'description' => $_product->description,
                'price' => $_product->price,
            ];

            if (count($points) > 100) {
                $syncPointsAndPayloads();
            }

            $existingProductIds[] = $_product->id;
            $newProductIds[] = $_product->id;

            $bar->advance();
        }

        // Sync remaining points and payloads
        $syncPointsAndPayloads();

        $bar->finish();

        // Populate reviews for new products
        $this->info('Populating reviews for new products...');
        $bar = $this->output->createProgressBar(count($newProductIds));
        foreach ($newProductIds as $productId) {
            for ($i = 0; $i < rand(1, 10); $i++) {
                Review::create([
                    'product_id' => $productId,
                    'score' => rand(1, 5),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info('Done!');

    }
}
