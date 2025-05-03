<?php

namespace App\Services;

use Qdrant\Config;
use Qdrant\Http\Builder;
use Qdrant\Models\Filter\Condition\MatchAny;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Models\VectorStruct;
use Qdrant\Qdrant;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\SearchRequest;

class QdrantService
{
    protected Qdrant $client;
    public function __construct(
        protected string $baseUri = 'http://qdrant:6333',
        protected string $collectionName = 'contents',
        protected string $pointName = 'content',
    ) {
        $config = new Config($baseUri);

        $transport = (new Builder())->build($config);
        $this->client = new Qdrant($transport);

        $this->createCollectionIfNotExists($collectionName);
    }

    public function checkCollectionExists(string $name)
    {
        return $this->client->collections($name)->exists()->offsetGet('result')['exists'] ?? false;
    }

    public function createCollectionIfNotExists(string $name, int $vectorsCount = 768, string $distance = VectorParams::DISTANCE_COSINE)
    {
        if ($this->checkCollectionExists($name)) {
            return;
        }
        $createCollection = new CreateCollection();
        $createCollection->addVector(new VectorParams($vectorsCount, $distance), $this->pointName);
        return $this->client->collections($name)->create($createCollection);
    }

    public function deleteCollection(string $name)
    {
        return $this->client->collections($name)->delete();
    }

    public function addVectors(string $collectionName, array $vectors, array $payloads = [], int $startId = 0)
    {
        $points = new PointsStruct();
        foreach ($vectors as $index => $vector) {
            if (!is_array($vector)) {
                continue;
            }
            $vectorStruct = new VectorStruct($vector, $this->pointName);

            $points->addPoint(new PointStruct($index + $startId, $vectorStruct, $payloads[$index] ?? []));
        }
        return $this->client->collections($collectionName)->points()->upsert($points);
    }

    public function search(string $collectionName, array $query, array $payloadIds = [], int $limit = 10)
    {
        $searchRequest = (new SearchRequest(new VectorStruct($query, $this->pointName)))
            ->setLimit($limit)
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ])
            ->setWithPayload(true);

        $filter = new Filter();
        if (count($payloadIds) > 0) {
            $filter->addMust(new MatchAny('id', $payloadIds));
            $searchRequest->setFilter($filter);
        }

        return $this->client->collections($collectionName)->points()->search($searchRequest)->offsetGet('result');
    }

}
