<?php

namespace App\Console\Commands;

use App\Services\ChatService;
use App\Services\OllamaService;
use App\Services\QdrantService;
use App\Services\ProductService;
use Illuminate\Console\Command;

class Playground extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:playground';

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
        $qdrant = new QdrantService();
        dd($qdrant->deleteCollection('products'));
        $ollama = new OllamaService();
        $productService = new ProductService();

        $chatService = new ChatService($ollama, $qdrant, $productService);


        $response = $chatService->chat('Maybelline Face Studio Master Hi-Light Light Booster Bronzer');
        foreach ($response as $chunk) {
            echo $chunk;
        }

        dd($response);

        $embeddings = $ollama->embed('Maybelline Face Studio Master Hi-Light Light Booster Bronzer')['embeddings'][0];

        dd($qdrant->search('products', $embeddings, [], 10)->offsetGet('result'));
    }
}
