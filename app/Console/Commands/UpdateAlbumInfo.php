<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Album;
use App\Services\SpotifyService;
use App\Services\MusicBrainzService;
use App\Services\LastFmService;
use Carbon\Carbon;

class UpdateAlbumInfo extends Command
{
    protected $signature = 'album:update-info';
    protected $description = 'Update album information from various sources';

    public function handle(SpotifyService $spotifyService, MusicBrainzService $musicBrainzService, LastFmService $lastFmService)
    {
        $albums = Album::whereNull('release_date')->orWhere('total_tracks', 0)->get();

        foreach ($albums as $album) {
            $this->info("Updating info for album: {$album->name}");

            // Try Spotify first
            if ($album->spotify_id) {
                $spotifyInfo = $spotifyService->getAlbumInfo($album->spotify_id);
                if ($spotifyInfo) {
                    $album->release_date = $this->formatDate($spotifyInfo['release_date']);
                    $album->total_tracks = $spotifyInfo['total_tracks'] ?? $album->total_tracks;
                    $album->save();
                    continue;
                }
            }

            // Try MusicBrainz
            $musicBrainzInfo = $musicBrainzService->searchAlbum($album->name, $album->artist->name);
            if ($musicBrainzInfo) {
                $album->release_date = $this->formatDate($musicBrainzInfo['release_date']);
                $album->total_tracks = $musicBrainzInfo['total_tracks'] ?? $album->total_tracks;
                $album->save();
                continue;
            }

            // Try Last.fm
            $lastFmInfo = $lastFmService->searchAlbum($album->name, $album->artist->name);
            if ($lastFmInfo) {
                $album->release_date = $this->formatDate($lastFmInfo['release_date']);
                $album->total_tracks = $lastFmInfo['total_tracks'] ?? $album->total_tracks;
                $album->save();
            }

            // If still no total_tracks, count the tracks in the database
            if ($album->total_tracks == 0) {
                $album->total_tracks = $album->tracks()->count();
                $album->save();
            }
        }

        $this->info('Album information update completed.');
    }

    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            // Nếu chỉ có năm
            if (strlen($date) == 4) {
                return Carbon::createFromFormat('Y', $date)->format('Y-01-01');
            }
            // Nếu có năm và tháng
            elseif (strlen($date) == 7) {
                return Carbon::createFromFormat('Y-m', $date)->format('Y-m-01');
            }
            // Nếu có đầy đủ ngày tháng năm
            else {
                return Carbon::parse($date)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            $this->error("Invalid date format for {$date}: " . $e->getMessage());
            return null;
        }
    }
}
