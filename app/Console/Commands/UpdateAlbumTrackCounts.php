<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Album;
use App\Models\Track;

class UpdateAlbumTrackCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'albums:update-track-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update track counts for all albums';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating track counts for all albums...');
        $albums = Album::all();
        foreach ($albums as $album) {
            $trackCount = $album->tracks()->count();
            $album->total_tracks = $trackCount;
            $album->save();
            $this->info("Updated album '{$album->name}': {$trackCount} tracks");

            // Thêm thông tin debug
            if ($trackCount == 0) {
                $this->warn("Album '{$album->name}' has no tracks. Album ID: {$album->id}");
            }
        }
        $this->info('Track count update completed.');

        // Thêm thông tin tổng quan
        $totalAlbums = Album::count();
        $totalTracks = Track::count();
        $this->info("Total albums: {$totalAlbums}");
        $this->info("Total tracks: {$totalTracks}");
    }
}
