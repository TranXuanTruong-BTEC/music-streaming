<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artist;
use App\Services\SpotifyService;

class UpdateArtistImages extends Command
{
    protected $signature = 'artists:update-images';
    protected $description = 'Update images for all artists';

    protected $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        parent::__construct();
        $this->spotifyService = $spotifyService;
    }

    public function handle()
    {
        $artists = Artist::whereNotNull('spotify_id')->get();

        $bar = $this->output->createProgressBar(count($artists));
        $bar->start();

        foreach ($artists as $artist) {
            $this->spotifyService->fetchAndSaveArtistImage($artist);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nArtist images update completed.");
    }
}
