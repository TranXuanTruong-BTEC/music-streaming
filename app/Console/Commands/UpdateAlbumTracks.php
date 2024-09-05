<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Album;
use App\Services\SpotifyService;

class UpdateAlbumTracks extends Command
{
    protected $signature = 'albums:update-tracks';
    protected $description = 'Update tracks for all albums';

    protected $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        parent::__construct();
        $this->spotifyService = $spotifyService;
    }

    public function handle()
    {
        $albums = Album::whereNotNull('spotify_id')->get();

        foreach ($albums as $album) {
            $this->info("Updating tracks for album: {$album->name}");
            $tracks = $this->spotifyService->fetchAlbumTracks($album->spotify_id);

            if ($tracks) {
                foreach ($tracks as $trackData) {
                    $album->tracks()->updateOrCreate(
                        ['spotify_id' => $trackData['id']],
                        [
                            'name' => $trackData['name'],
                            'artist_id' => $album->artist_id,
                            'duration_ms' => $trackData['duration_ms'],
                            'track_number' => $trackData['track_number'],
                        ]
                    );
                }
                $this->info("Updated {$album->tracks->count()} tracks for album: {$album->name}");
            } else {
                $this->warn("Failed to fetch tracks for album: {$album->name}");
            }
        }

        $this->info('Album tracks update completed.');
    }
}
