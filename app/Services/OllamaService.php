<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use Generator;
use Illuminate\Support\Facades\Http;

class OllamaService
{
    public function __construct(
        protected string $model = 'qwen3:0.6b',
        protected string $embedModel = 'nomic-embed-text',
        protected string $baseUri = 'http://ollama:11434',
    ) {
        //
    }

    public function chat(string $message, array $history = [], bool $stream = true): string|Generator
    {
        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant for e-commerce platform. You are given products and a user message. You need to answer the user message based on the products.',
                ],
                ...$history,
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
            'keep_alive' => 0,
            'stream' => $stream,
        ];

        if ($stream) {
            $response = Http::timeout(60 * 5)
                ->withOptions(['stream' => true])
                ->post("{$this->baseUri}/api/chat", $payload);

            $streamBody = $response->toPsrResponse()->getBody();
            while (!$streamBody->eof()) {
                $chunk = $streamBody->read(8192);
                if ($chunk !== false && strlen($chunk) > 0) {
                    yield $chunk;
                }
            }
            return;
        } else {
            $response = Http::timeout(60 * 5)->post("{$this->baseUri}/api/chat", $payload);
            $content = $response->json('message.content');
            $options = $content ? json_decode($content, true) : [];

            if (empty($options)) {
                // Not a product-related query, handle accordingly
            }
            return $options;
        }
    }

    public function generateText(string $prompt, bool $stream = false): string|Generator
    {
        $response = Http::post("{$this->baseUri}/api/generate", [
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => $stream,
        ]);

        if ($stream) {
            $streamBody = $response->toPsrResponse()->getBody();
            while (!$streamBody->eof()) {
                $chunk = $streamBody->read(8192);
                yield $chunk;
            }
            return;
        }

        return $response->body();
    }

    public function embed(string|array $text): array
    {
        $response = Http::post("{$this->baseUri}/api/embed", [
            'model' => $this->embedModel,
            'input' => $text,
        ]);

        return $response->json();
    }

    public function getStructuredProductOptions(string $message): array
    {
        $formatSchema = [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                ],
                'categories' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'brands' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'stock_min' => ['type' => ['integer', 'null']],
                'price_min' => ['type' => ['integer', 'null']],
                'price_max' => ['type' => ['integer', 'null']],
                'is_active' => ['type' => ['boolean', 'null']],
            ],
            'required' => [
                'query',
                'categories',
                'brands',
                'stock_min',
                'price_min',
                'price_max',
                'is_active'
            ],
        ];

        $availableCategories = Category::all()->pluck('name')->toArray();
        $availableBrands = Brand::all()->pluck('name')->toArray();

        $categories = implode(', ', $availableCategories);
        $brands = implode(', ', $availableBrands);

        $payload = [
            'model' => $this->model,
            'keep_alive' => 0,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => <<<PROMPT
You are a helpful assistant for an e-commerce platform. Your task is to extract product filter options from the user's message and return them as structured JSON.

If the user's message is NOT relevant to product search or filtering (for example, if it is a greeting, a general question, nonsense, or unrelated to products), you MUST return an empty object: {}.

When relevant, your JSON output should include:
- query: string
- categories: array of category names (strings)
- brands: array of brand names (strings)
- stock_min: integer or null
- price_min: integer or null
- price_max: integer or null
- is_active: boolean or null

Here are the available categories: {$categories}
Here are the available brands: {$brands}

Examples:
User: \"Show me all Nike shoes under $100\"
Output: {\"categories\": [<category_id_for_shoes>], \"brands\": [<brand_id_for_Nike>], \"stock_min\": null, \"price_min\": null, \"price_max\": 100, \"is_active\": null}

User: \"Hello, how are you?\"
Output: {}

User: \"asdf\"
Output: {}

If you are unsure or the message is ambiguous, return {}.
PROMPT
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
            'stream' => false,
            'format' => $formatSchema,
        ];

        $response = Http::timeout(60 * 5)->post("{$this->baseUri}/api/chat", $payload);
        $content = $response->json('message.content');
        $options = $content ? json_decode($content, true) : [];

        if (empty($options)) {
            return [];
        }

        if (isset($options['categories'])) {
            $options['categories'] = array_map(function ($category) use ($availableCategories) {
                return array_search($category, $availableCategories) ?? null;
            }, $options['categories']);

            $options['categories'] = array_filter($options['categories'], function ($category) {
                return $category;
            });
        }

        if (isset($options['brands'])) {
            $options['brands'] = array_map(function ($brand) use ($availableBrands) {
                return array_search($brand, $availableBrands) ?? null;
            }, $options['brands']);

            $options['brands'] = array_filter($options['brands'], function ($brand) {
                return $brand;
            });
        }
        return $options;
    }
}
