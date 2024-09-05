<?php

namespace App\Services;

use App\Interfaces\ImageSearchInterface;
use App\Traits\RateLimiter;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UnsplashImageService implements ImageSearchInterface
{
    use RateLimiter;

    protected $client;
    protected $unsplashApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->unsplashApiKey = config('services.unsplash.access_key');
    }

    public function searchImage($query)
    {
        $cacheKey = 'unsplash_image_' . md5($query);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($query) {
            if ($this->hitRateLimit('unsplash_search', 50, 1)) {
                return null;
            }

            try {
                $response = $this->client->get('https://api.unsplash.com/search/photos', [
                    'headers' => [
                        'Authorization' => 'Client-ID ' . $this->unsplashApiKey,
                    ],
                    'query' => [
                        'query' => $query,
                        'per_page' => 1,
                    ]
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (isset($result['results'][0]['urls']['regular'])) {
                    return $result['results'][0]['urls']['regular'];
                }
            } catch (\Exception $e) {
                Log::error('Unsplash image search error: ' . $e->getMessage());
            }

            return null;
        });
    }
}
