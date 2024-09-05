<?php

namespace App\Services;

use App\Models\Song;
use App\Models\Artist;
use App\Models\Album;

class AIRecommendationService
{
    protected $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    public function analyzeAndAddNewReleases()
    {
        $newReleases = $this->spotifyService->getNewReleases(50);
        $addedSongs = 0;

        foreach ($newReleases as $release) {
            if ($this->shouldAddAlbum($release)) {
                $addedSongs += $this->addAlbumTracks($release);
            }
        }

        return $addedSongs;
    }

    protected function shouldAddAlbum($album)
    {
        // Implement your logic here. For now, we'll add all albums
        return true;
    }

    protected function addAlbumTracks($album)
    {
        $artist = Artist::firstOrCreate(
            ['spotify_id' => $album->artists[0]->id],
            ['name' => $album->artists[0]->name]
        );

        $albumModel = Album::firstOrCreate(
            ['spotify_id' => $album->id],
            [
                'name' => $album->name,
                'artist_id' => $artist->id,
                'release_date' => $album->release_date,
                'image_url' => $album->images[0]->url ?? null,
            ]
        );

        $tracks = $this->spotifyService->getAlbumTracks($album->id);
        $addedTracks = 0;

        foreach ($tracks as $track) {
            Song::firstOrCreate(
                ['spotify_id' => $track->id],
                [
                    'name' => $track->name,
                    'artist_id' => $artist->id,
                    'album_id' => $albumModel->id,
                    'duration' => $track->duration_ms / 1000, // Convert to seconds
                ]
            );
            $addedTracks++;
        }

        return $addedTracks;
    }
}