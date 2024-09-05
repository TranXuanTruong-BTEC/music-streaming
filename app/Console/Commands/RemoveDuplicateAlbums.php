<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Album;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateAlbums extends Command
{
    protected $signature = 'albums:remove-duplicates';
    protected $description = 'Remove duplicate albums from the database';

    public function handle()
    {
        $this->info('Starting to remove duplicate albums...');

        $duplicates = Album::select('name', 'artist_id', DB::raw('COUNT(*) as count'))
            ->groupBy('name', 'artist_id')
            ->having('count', '>', 1)
            ->get();

        $totalRemoved = 0;

        foreach ($duplicates as $duplicate) {
            $this->info("Found duplicate: {$duplicate->name} by artist ID {$duplicate->artist_id}");

            $albums = Album::where('name', $duplicate->name)
                           ->where('artist_id', $duplicate->artist_id)
                           ->orderBy('created_at', 'desc')
                           ->get();

            // Keep the most recent album
            $keepAlbum = $albums->shift();
            $this->info("Keeping album ID {$keepAlbum->id} (created at {$keepAlbum->created_at})");

            // Remove the rest
            foreach ($albums as $album) {
                $this->info("Removing album ID {$album->id} (created at {$album->created_at})");
                
                // Move tracks to the kept album
                DB::table('tracks')
                    ->where('album_id', $album->id)
                    ->update(['album_id' => $keepAlbum->id]);

                $album->delete();
                $totalRemoved++;
            }
        }

        $this->info("Removed {$totalRemoved} duplicate albums.");
    }
}
