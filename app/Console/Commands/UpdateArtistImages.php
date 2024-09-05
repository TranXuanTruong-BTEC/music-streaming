<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Services\ImageSearchService;
use App\Services\UnsplashImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateArtistImages extends Command
{
    protected $signature = 'artists:update-images';
    protected $description = 'Update artist images from various sources';

    public function handle(ImageSearchService $googleImageService, UnsplashImageService $unsplashImageService)
    {
        $artists = Artist::where('image_search_attempted', false)->get();

        foreach ($artists as $artist) {
            $this->info("Updating image for artist: {$artist->name}");

            $existingArtist = Artist::where('name', $artist->name)
                                    ->where('image_url', '!=', null)
                                    ->first();

            if ($existingArtist) {
                $artist->image_url = $existingArtist->image_url;
                $artist->image_search_attempted = true;
                $artist->save();
                $this->info("Used existing image for {$artist->name}");
                continue;
            }

            $imageUrl = $this->searchImage($artist->name, $googleImageService, $unsplashImageService);

            if ($imageUrl) {
                $this->saveArtistImage($artist, $imageUrl);
            } else {
                $this->warn("No image found for artist: {$artist->name}");
            }

            $artist->image_search_attempted = true;
            $artist->save();

            sleep(2); // Add a small delay between requests
        }

        $this->info("Artist image update completed.");
    }

    private function searchImage($query, ...$services)
    {
        foreach ($services as $service) {
            $imageUrl = $service->searchImage($query . " singer");
            if ($imageUrl) {
                return $imageUrl;
            }
        }
        return null;
    }

    private function saveArtistImage($artist, $imageUrl)
    {
        try {
            $imageContents = file_get_contents($imageUrl);
            $filename = 'artists/' . Str::slug($artist->name) . '.jpg';
            Storage::disk('public')->put($filename, $imageContents);

            $artist->image_url = $filename;
            $artist->save();

            $this->info("Image updated successfully for {$artist->name}.");
        } catch (\Exception $e) {
            $this->error("Error saving image for {$artist->name}: " . $e->getMessage());
        }
    }
}
