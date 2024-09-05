<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MusicBrainzService
{
    public function searchAlbum($albumName, $artistName)
    {
        $response = Http::get('https://musicbrainz.org/ws/2/release', [
            'query' => $albumName . ' ' . $artistName,
            'fmt' => 'json',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['releases'][0])) {
                $release = $data['releases'][0];
                return [
                    'release_date' => $release['date'] ?? null,
                    'total_tracks' => $release['track-count'] ?? null,
                ];
            }
        }

        return null;
    }
}
