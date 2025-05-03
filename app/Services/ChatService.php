<?php

namespace App\Services;

use Generator;

class ChatService
{

    public function __construct(
        protected OllamaService $ollamaService,
        protected QdrantService $qdrantService,
        protected ProductService $productService,
    ) {
    }

    public function chat(
        string $message,
        array $history = [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant for an e-commerce platform.',
            ],
        ],
        int $limit = 10
    ): Generator {
        if (empty($message)) {
            yield "Please enter a message";
        }

        yield "Finding products... \n";

        $embeddings = $this->ollamaService->embed($message)['embeddings'][0];

        $qdrantProducts = $this->qdrantService->search('products', $embeddings, [], 100);

        $filters = $this->ollamaService->getStructuredProductOptions($message);
        $filteredProducts = [];

        if (count($filters) > 0) {
            if (isset($filters['query'])) {
                $message = $filters['query'];
            }

            [$filteredProducts, $total] = $this->productService->searchProducts($filters);

            // iterate over qdrantProducts and if the product is in the filteredProducts array, sort it to the top of the qdrantProducts array
            $filteredProductIds = [];
            if (!$filteredProducts->isEmpty()) {
                $filteredProductIds = $filteredProducts->pluck('id')->toArray();
            }

            usort($qdrantProducts, function ($a, $b) use ($filteredProductIds) {
                $aId = $a['payload']['id'] ?? null;
                $bId = $b['payload']['id'] ?? null;
                $aInFiltered = in_array($aId, $filteredProductIds);
                $bInFiltered = in_array($bId, $filteredProductIds);
                if ($aInFiltered === $bInFiltered) {
                    return 0;
                }
                return $aInFiltered ? -1 : 1;
            });

        }

        // take top 10 products from qdrantProducts
        $products = array_slice($qdrantProducts, 0, $limit);

        $productIds = array_map(function ($product) {
            return $product['payload']['id'];
        }, $products);

        $suggestedProducts = $this->productService->getProductsByIds($productIds)->toArray();

        $response = $this->ollamaService->chat("Using the products below: " . json_encode($products) . " answer the following question: " . $message, $history);

        yield "Generating response... \n";

        foreach ($response as $chunk) {
            if (preg_match_all('/"content"\s*:\s*"(.*?)"/s', $chunk, $contentMatches)) {
                foreach ($contentMatches[1] as $content) {
                    yield $content;
                }
            } else {
                // yield "<original_response>" . $chunk . "</original_response>"; // fallback: yield the raw JSON if not matched
            }
        }

        yield " \n <suggestedProducts>" . json_encode($suggestedProducts) . "</suggestedProducts>";
    }

}
