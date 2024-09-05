<?php

namespace App\Services;

use App\Interfaces\ImageSearchInterface; // Thêm dòng này
use App\Traits\RateLimiter;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImageSearchService implements ImageSearchInterface
{
    use RateLimiter;

    protected $client;
    protected $googleApiKey;
    protected $googleSearchEngineId;

    public function __construct()
    {
        $this->client = new Client();
        $this->googleApiKey = config('services.google.search_api_key');
        $this->googleSearchEngineId = config('services.google.search_engine_id');
    }

    public function searchImage($query)
    {
        $cacheKey = 'artist_image_' . md5($query);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($query) {
            if ($this->hitRateLimit('image_search', 100, 1)) {
                return null;
            }

            try {
                $response = $this->client->get('https://www.googleapis.com/customsearch/v1', [
                    'query' => [
                        'key' => $this->googleApiKey,
                        'cx' => $this->googleSearchEngineId,
                        'q' => $query,
                        'searchType' => 'image',
                        'num' => 1,
                    ]
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (isset($result['items'][0]['link'])) {
                    return $result['items'][0]['link'];
                }
            } catch (\Exception $e) {
                Log::error('Image search error: ' . $e->getMessage());
            }

            return null;
        });
    }
}
