<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LastFmService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.lastfm.api_key');
    }

    public function searchAlbum($albumName, $artistName)
    {
        $response = Http::get('http://ws.audioscrobbler.com/2.0/', [
            'method' => 'album.getinfo',
            'api_key' => $this->apiKey,
            'artist' => $artistName,
            'album' => $albumName,
            'format' => 'json',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['album'])) {
                return [
                    'release_date' => $data['album']['wiki']['published'] ?? null,
                    'total_tracks' => count($data['album']['tracks']['track'] ?? []),
                ];
            }
        }

        return null;
    }
}
