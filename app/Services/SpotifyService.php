<?php

namespace App\Services;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Exception;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SpotifyService
{
    protected $api;

    public function __construct()
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new Exception('Spotify client ID or client secret is missing');
        }

        $session = new Session($clientId, $clientSecret);

        try {
            $session->requestCredentialsToken();
            $accessToken = $session->getAccessToken();

            $this->api = new SpotifyWebAPI();
            $this->api->setAccessToken($accessToken);
        } catch (Exception $e) {
            throw new Exception('Failed to authenticate with Spotify: ' . $e->getMessage());
        }
    }

    public function getNewReleases($limit = 20)
    {
        return $this->api->getNewReleases(['limit' => $limit])->albums->items;
    }

    public function getAlbumTracks($albumId)
    {
        return $this->api->getAlbumTracks($albumId)->items;
    }

    public function getTrackDetails($trackId)
    {
        return $this->api->getTrack($trackId);
    }

    public function getArtist($artistId)
    {
        return $this->api->getArtist($artistId);
    }

    protected function getAccessToken()
    {
        $session = new Session(
            config('services.spotify.client_id'),
            config('services.spotify.client_secret')
        );
        $session->requestCredentialsToken();
        return $session->getAccessToken();
    }

    public function fetchAndSaveNewReleases()
    {
        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)->get('https://api.spotify.com/v1/browse/new-releases', [
            'country' => 'VN',
            'limit' => 50,
        ]);

        if ($response->successful()) {
            $newReleases = $response->json()['albums']['items'];
            foreach ($newReleases as $release) {
                $artist = Artist::firstOrCreate(
                    ['spotify_id' => $release['artists'][0]['id']],
                    ['name' => $release['artists'][0]['name']]
                );

                $album = Album::updateOrCreate(
                    ['spotify_id' => $release['id']],
                    [
                        'name' => $release['name'],
                        'artist_id' => $artist->id,
                        'release_date' => $release['release_date'],
                        'total_tracks' => $release['total_tracks'],
                    ]
                );

                // Fetch and save tracks for this album
                $tracks = $this->fetchAlbumTracks($release['id']);
                if ($tracks) {
                    foreach ($tracks as $trackData) {
                        Track::updateOrCreate(
                            ['spotify_id' => $trackData['id']],
                            [
                                'name' => $trackData['name'],
                                'album_id' => $album->id,
                                'artist_id' => $artist->id,
                                'duration_ms' => $trackData['duration_ms'],
                                'track_number' => $trackData['track_number'],
                            ]
                        );
                    }
                }
            }
        }

        // Sau khi lưu tất cả albums mới
        $this->removeDuplicateAlbums();
    }

    public function fetchAlbumTracks($albumId)
    {
        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)->get("https://api.spotify.com/v1/albums/{$albumId}/tracks");

        if ($response->successful()) {
            return $response->json()['items'];
        }

        return null;
    }

    private function updateAlbumInfo($album)
    {
        if (!$album->release_date || $album->total_tracks == 0) {
            $musicBrainzService = new MusicBrainzService();
            $albumInfo = $musicBrainzService->searchAlbum($album->name, $album->artist->name);

            if ($albumInfo) {
                $album->release_date = $albumInfo['release_date'] ?? $album->release_date;
                $album->total_tracks = $albumInfo['total_tracks'] ?? $album->total_tracks;
                $album->save();
            }

            if (!$album->release_date || $album->total_tracks == 0) {
                $lastFmService = new LastFmService();
                $albumInfo = $lastFmService->searchAlbum($album->name, $album->artist->name);

                if ($albumInfo) {
                    $album->release_date = $albumInfo['release_date'] ?? $album->release_date;
                    $album->total_tracks = $albumInfo['total_tracks'] ?? $album->total_tracks;
                    $album->save();
                }
            }
        }

        // Nếu vẫn không có thông tin, sử dụng số lượng track đã lưu
        if ($album->total_tracks == 0) {
            $album->total_tracks = $album->tracks()->count();
            $album->save();
        }
    }

    private function removeDuplicateAlbums()
    {
        \Artisan::call('albums:remove-duplicates');
    }

    public function fetchAndSaveArtistImage($artist)
    {
        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)->get("https://api.spotify.com/v1/artists/{$artist->spotify_id}");

        if ($response->successful()) {
            $artistData = $response->json();
            if (!empty($artistData['images'])) {
                $imageUrl = $artistData['images'][0]['url'];
                $imageContents = file_get_contents($imageUrl);
                $filename = 'artists/' . $artist->id . '_' . time() . '.jpg';
                Storage::disk('public')->put($filename, $imageContents);
                $artist->image_url = $filename;
                $artist->save();
                return true;
            }
        }
        return false;
    }
}