<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artist;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RemoveDuplicateData extends Command
{
    protected $signature = 'data:remove-duplicates';
    protected $description = 'Remove duplicate artists, albums, tracks, and images';

    public function handle()
    {
        $this->info('Starting to remove duplicate data...');

        $this->removeDuplicateArtists();
        $this->removeDuplicateAlbums();
        $this->removeDuplicateTracks();
        $this->removeDuplicateImages();
        $this->updateAllAlbumTrackCounts();

        $this->info('Duplicate data removal and track count update completed.');
    }

    private function removeDuplicateArtists()
    {
        $this->info('Removing duplicate artists...');
        $duplicates = DB::table('artists')
            ->select(DB::raw('LOWER(name) as lower_name'), DB::raw('COUNT(*) as count'))
            ->groupBy('lower_name')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $artists = Artist::whereRaw('LOWER(name) = ?', [$duplicate->lower_name])
                             ->orderBy('created_at', 'desc')
                             ->get();

            $keepArtist = $artists->shift();
            foreach ($artists as $artist) {
                Album::where('artist_id', $artist->id)->update(['artist_id' => $keepArtist->id]);
                Track::where('artist_id', $artist->id)->update(['artist_id' => $keepArtist->id]);
                $artist->delete();
            }
        }
    }

    private function removeDuplicateAlbums()
    {
        $this->info('Removing duplicate albums...');
        $duplicates = DB::table('albums')
            ->select(DB::raw('LOWER(name) as lower_name'), 'artist_id', DB::raw('COUNT(*) as count'))
            ->groupBy('lower_name', 'artist_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $albums = Album::whereRaw('LOWER(name) = ?', [$duplicate->lower_name])
                           ->where('artist_id', $duplicate->artist_id)
                           ->orderBy('created_at', 'desc')
                           ->get();

            $keepAlbum = $albums->shift();
            foreach ($albums as $album) {
                // Chuyển các bài hát sang album được giữ lại
                Track::where('album_id', $album->id)->update(['album_id' => $keepAlbum->id]);
                $album->delete();
            }

            // Cập nhật số lượng bài hát cho album được giữ lại
            $trackCount = Track::where('album_id', $keepAlbum->id)->count();
            $keepAlbum->total_tracks = $trackCount;
            $keepAlbum->save();
        }
    }

    private function removeDuplicateTracks()
    {
        $this->info('Removing duplicate tracks...');
        $duplicates = DB::table('tracks')
            ->select(DB::raw('LOWER(name) as lower_name'), 'album_id', 'artist_id', DB::raw('COUNT(*) as count'))
            ->groupBy('lower_name', 'album_id', 'artist_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $tracks = Track::whereRaw('LOWER(name) = ?', [$duplicate->lower_name])
                           ->where('album_id', $duplicate->album_id)
                           ->where('artist_id', $duplicate->artist_id)
                           ->orderBy('created_at', 'desc')
                           ->get();

            $keepTrack = $tracks->shift();
            foreach ($tracks as $track) {
                $track->delete();
            }
        }
    }

    private function removeDuplicateImages()
    {
        $this->info('Removing duplicate images...');
        $artists = Artist::all();
        $processedImages = [];

        foreach ($artists as $artist) {
            if ($artist->image_url && !in_array($artist->image_url, $processedImages)) {
                $processedImages[] = $artist->image_url;
            } elseif ($artist->image_url && in_array($artist->image_url, $processedImages)) {
                Storage::delete($artist->image_url);
                $artist->image_url = null;
                $artist->save();
            }
        }

        $albums = Album::all();
        $processedImages = [];

        foreach ($albums as $album) {
            if ($album->image_url && !in_array($album->image_url, $processedImages)) {
                $processedImages[] = $album->image_url;
            } elseif ($album->image_url && in_array($album->image_url, $processedImages)) {
                Storage::delete($album->image_url);
                $album->image_url = null;
                $album->save();
            }
        }
    }

    private function updateAllAlbumTrackCounts()
    {
        $this->info('Updating track counts for all albums...');
        $albums = Album::all();
        foreach ($albums as $album) {
            $trackCount = Track::where('album_id', $album->id)->count();
            $album->total_tracks = $trackCount;
            $album->save();
        }
    }
}
