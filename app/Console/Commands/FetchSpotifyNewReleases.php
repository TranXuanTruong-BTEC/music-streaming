<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AIRecommendationService;
use Exception;

class FetchSpotifyNewReleases extends Command
{
    protected $signature = 'spotify:fetch-new-releases';
    protected $description = 'Fetch and analyze new releases from Spotify';

    public function handle(AIRecommendationService $aiService)
    {
        try {
            $addedSongs = $aiService->analyzeAndAddNewReleases();
            $this->info("Added {$addedSongs} new songs to the database.");
        } catch (Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}